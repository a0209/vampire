<?php
/**
 * Created by PhpStorm.
 * User: XinCheng
 * Date: 2019/6/26
 * Time: 11:38
 */

namespace app\api\model;


class ThirdApp extends BaseModel
{
	public static function check($ac, $se)
	{
		$app = self::where('app_id', '=', $ac)
			->where('app_secret', '=', $se)
			->find();

		return $app;
	}
}