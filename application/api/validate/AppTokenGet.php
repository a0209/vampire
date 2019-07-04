<?php
/**
 * Created by PhpStorm.
 * User: XinCheng
 * Date: 2019/6/26
 * Time: 11:30
 */

namespace app\api\validate;


class AppTokenGet extends BaseValidate
{
	protected $rule = [
		'ac' => 'require|isNotEmpty',
		'se' => 'require|isNotEmpty'
	];
}