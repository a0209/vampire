<?php 

namespace app\api\service;

use app\api\model\Order as OrderModel;
use app\api\service\Order as OrderService;
use app\lib\exception\OrderException;
use app\lib\exception\TokenException;
use app\api\service\Token;
use app\lib\enum\OrderStatusEnum;
use think\Exception;
use think\Loader;

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
	}

	// 微信预订单
	private function makeWxPreOrder()
	{
		// openid
		$openid = Token::getCurrentTokenVar('openid');

		if(!$openid){
			throw new TokenException();
		}
		$wxOrderData = new \WxPayUnifiedOrder();
	}

	// 检测前三个
	// 订单号可能根本不存在
	// 订单号确实是存在的, 但是, 订单号和当前用户是不匹配的
	// 订单有可能已经被支付过
	private function checkOrderValid()
	{
		$order = OrderModel::where('id', '=', $this->orderID)->find();

		if(!$order){
			throw new OrderException();
		}

		if(!Token::isValidOperate($order->user_id)){
			throw new TokenException([
				'msg' => '订单与用户不匹配',
				'errorCode' => 10003
			]);
		}

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