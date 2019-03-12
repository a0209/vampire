<?php 

namespace app\api\service;

class Token
{
	public function generateToken()
	{
		// 32个字符组成一组随机字符串
		$randChars = getRandChar();
		// 用三组字符串,进行md5加密
		$timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
		//salt 盐
		$salt = config('secure.tokem_salt');

		return md5($randChars.$timestamp.$salt);
	}
}