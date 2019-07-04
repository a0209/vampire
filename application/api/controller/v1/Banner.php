<?php
namespace app\api\controller\v1;

use app\api\validate\IDMustBePostiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\BannerMissException;

class Banner
{
	/*
	首页轮播图
	获取指定id的banner信息
	@url /banner/:id
	@http get
	@id banner的id号
	*/
	public function getBanner($id)
	{
		(new IDMustBePostiveInt())->goCheck();
		$banner = BannerModel::getBannerByID($id);

		if(!$banner){
			throw new BannerMissException();
		}

		return $banner;
	}
}
