<?php 

namespace app\api\service;

use think\Exception;

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
		
	}
}