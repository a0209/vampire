<?php 

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use app\lib\enum\OrderStatusEnum;
use think\Exception;
use think\Loader;
use think\Log;

// extend/WxPay/WxPay.Api.php
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class Pay
{
	private $orderID;
	private $orderNO;

	function __construct($orderID)
	{
		if(!$orderID)
		{
			throw new Exception('订单号不许为null');
		}
		$this->orderID = $orderID;
	}

	public function pay()
	{
		$this->checkOrderValid();
		// 进行库存量检测
		$orderService = new OrderService();
		$status = $orderService->checkOrderStock($this->orderID);

		if(!$status['pass']){
			return $status;
		}

		return $this->makeWxPreOrder($status['orderPrice']);
	}

	// 微信预订单
	private function makeWxPreOrder($totalPrice)
	{
		// openid
		$openid = Token::getCurrentTokenVar('openid');

		if(!$openid){
			throw new TokenException();
		}
		// 微信预订单需要的参数
		$wxOrderData = new \WxPayUnifiedOrder();
		$wxOrderData->SetOut_trade_no($this->orderNO);	// 订单号
		$wxOrderData->SetTrade_Type('JSAPI');			// 交易类型(固定)
		$wxOrderData->SetTotal_fee($totalPrice * 100);	// 支付总金额(单位:分),所以要乘以100
		$wxOrderData->SetBody('零食商贩');				// 商品的一个简要描述
		$wxOrderData->SetOpenid($openid);				
		$wxOrderData->SetNotify_url(config('secure.pay_back_url'));		// 接收微信支付结果的接口(地址)
		return $this->getPaySignature($wxOrderData);
	}

	// 向微信发送预订单
	private function getPaySignature($wxOrderData)
	{
		// 服务器调用微信的统一下单接口
		// $wxOrder返回的数据(数组形式):	
		//	appid(小程序id), mch_id(商户号id), nonce_str(随机字符串), prepay_id, 
		//	result_code, return_code, return_msg, sign(加密签名), trade_type
		$wxOrder = \WxPayApi::unifiedOrder($wxOrderData);

		if($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS'){
			LOG::record($wxOrder, 'error');
			LOG::record('获取预支付订单失败', 'error');
		}
		// prepay_id 向用户主动推送微信模板消息的时候需要的数据(先保存,CMS时用)
		$this->recordPreOrder($wxOrder);
		$signature = $this->sign($wxOrder);

		return $signature;
	}

	// 封装返回给客户端的参数
	private function sign($wxOrder)
	{
		$jsApiPayData = new \WxPayJsApiPay();
		$jsApiPayData->SetAppid(config('wx.app_id'));
		$jsApiPayData->SetTimeStamp((string)time());
		$rand = md5(time() . mt_rand(0, 1000));
		$jsApiPayData->SetNoceStr($rand);
		$jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
		$jsApiPayData->SetSignType('md5');
		$sign = $jsApiPayData->MakeSign();
		$rawValues = $jsApiPayData->GetValues();
		$rawValues['paySign'] = $sign;
		unset($rawValues['appId']);

		return $rawValues;
	}

	// 处理prepay_id(保存到数据库)
	private function recordPreOrder($wxOrder)
	{
		OrderModel::where('id', '=', $this->orderID)
			->update(['prepay_id' => $wxOrder['prepay_id']]);
	}

	// 检测
	private function checkOrderValid()
	{
		// 订单号可能根本不存在
		$order = OrderModel::where('id', '=', $this->orderID)->find();

		if(!$order){
			throw new OrderException();
		}

		// 订单号确实是存在的, 但是, 订单号和当前用户是不匹配的
		if(!Token::isValidOperate($order->user_id)){
			throw new TokenException([
				'msg' => '订单与用户不匹配',
				'errorCode' => 10003
			]);
		}

		// 订单有可能已经被支付过
		if($order->status != OrderStatusEnum::UNPAID){
			throw new OrderException([
				'msg' => '订单已支付过啦',
				'errorCode' => 80003,
				'code' => 400
			]);
		}
		$this->orderNO = $order->order_no;

		return true;
	}
}