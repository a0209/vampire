<?php 

namespace app\api\service;

use think\Cache;
use think\Request;
use app\lib\exception\TokenException;
use app\lib\enum\ScopeEnum;
use app\lib\exception\ForbiddenException;

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

	// 获取缓存中指定的值
	public static function getCurrentTokenVar($key)
	{
		$token = Request::instance()->header('token');
		$vars = Cache::get($token);

		if(!$vars){
			throw new TokenException();
		}else{

			if(!is_array($vars)){
				$vars = json_decode($vars, true);
			}

			if(array_key_exists($key, $vars)){
				return $vars[$key];
			}else{
				throw new Exception('尝试获取的Token变量并不存在');
			}
		}
	}

	// 从缓存中获取用户id
	public static function getCurrentUid()
	{
		$uid = self::getCurrentTokenVar('uid');

		return $uid;
	}

	// 用户和CMS管理员都可以访问的权限
	public static function needPrimaryScope()
	{
		$scope = self::getCurrentTokenVar('scope');

		if($scope){

			if($scope >= ScopeEnum::User){
				return true;
			}else{
				throw new ForbiddenException();
			}
		}else{
			throw new TokenException();
		}
	}

	// 只有用户才能访问的接口权限
	public static function needExclusiveScope()
	{
		$scope = self::getCurrentTokenVar('scope');

		if($scope){

			if($scope == ScopeEnum::User){
				return true;
			}else{
				throw new ForbiddenException();
			}
		}else{
			throw new TokenException();
		}
	}
}