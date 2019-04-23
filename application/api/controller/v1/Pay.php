<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\Service\Pay as PayService;
use app\api\service\WxNotify;

class Pay extends BaseController
{
	protected $beforeActionList = [
		'checkExclusiveScope' => ['only' => 'getPreOrder']
	];

	// 向微信发送微信需要的预订单
	public function getPreOrder($id='')
	{
		(new IDMustBePostiveInt())->gocheck();
		$pay = new PayService($id);

		return $pay->pay();
	}

	// 接受微信返回的支付结果(通知)
	public function receviceNotify()
	{
		// 通知频率为15/15/30/180/1800/1800/1800/1800/3600 单位: 秒

		// 1.检测库存量, 超卖
		// 2.更新这个订单的status状态
		// 3.减库存
		// 如果处理成功, 我们返回微信成功处理的消息. 否则, 我们需要返回没有成功处理

		// 微信返回参数的特点
		// post传递; xml格式:不会携带参数
		$notify = new WxNotify();
		$notify->Handle();
	}
}