<?php 

namespace app\api\model;

class Order extends BaseModel
{
	protected $hidden = ['user_id', 'delete_time', 'update_time'];
	protected $autoWriteTimestamp = true;

	// protected $createTime = 'create_timestamp';

	// 根据用户id获取历史订单
	public static function getSummaryByUser($uid, $page=1, $size=15)
	{
		$pagingData = self::where('user_id', '=', $uid)
			->order('create_time desc')
			->paginate($size, true, ['page' => $page]);

		return $pagingData;
	}

	// 获取器 把订单详情中获取的部分数据转换成json对象格式
	public function getSnapItemsAttr($value)
	{
		if(empty($value)){

			return null;
		}

		return json_decode($value);
	}

	public function getSnapAddressAttr($value)
	{
		if(empty($value)){
			return null;
		}

		return json_decode($value);
	}
}