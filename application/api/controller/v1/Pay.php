<?php

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\validate\IDMustBePostiveInt;

class Pay extends BaseController
{
	protected $beforeActionList = [
		'checkExclusiveScope' => ['only' => 'getPreOrder']
	];

	// 向微信发送微信需要的预订单
	public function getPreOrder($id='')
	{
		(new IDMustBePostiveInt())->gocheck();
	}
}