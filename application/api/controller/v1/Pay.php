<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;
use app\api\Service\Pay as PayService;

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
	}
}