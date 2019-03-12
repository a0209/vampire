<?php 

namespace app\api\validate;

class AddressNew extends BaseValidate
{
	protected $rule = [
		'name' => 'require|isNotEmpty',
		'mobile' => 'require|isMobile',
		'province' => 'require|isNotEmpty',
		'city' => 'require|isNotEmpty',
		'country' => 'reuqire|isNotEmpty',
		'detail' => 'reuqire|isNotEmpty'
	];
}