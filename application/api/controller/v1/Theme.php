<?php

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\validate\IDMustBePostiveInt;
use app\api\model\Theme as ThemeModel;
use app\lib\exception\ThemeException;

class Theme
{
	/*
		首页精选主题
	 * @url /theme?ids=id1,id2,id3...
	   @return 一组theme模型
	 */
	public function getSimpleList($ids=''){
		(new IDCollection())->gocheck();
		$ids = explode(',', $ids);
		$result = ThemeModel::with('topicImg,headImg')->select($ids);

		if($result->isEmpty()){
			throw new ThemeException();
		}

		return $result;
	}

	/*
		主题对应的列表
		@url /theme/:id
	*/
	public function getComplexOne($id){
		(new IDMustBePostiveInt())->goCheck();
		$theme = ThemeModel::getThemeWithProducts($id);

		if(!$theme){
			throw new ThemeException();
		}

		return $theme;
	}
}