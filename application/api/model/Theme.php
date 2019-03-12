<?php

namespace app\api\model;

class Theme extends BaseModel
{
	protected $hidden = ['delete_time','update_time','topic_img_id','head_img_id'];

	// 分类图
	public function topicImg()
	{
		//	$this->hasOne()
		return $this->belongsTo('Image', 'topic_img_id', 'id');
	}

	// 列表头图
	public function headImg()
	{
		return $this->belongsTo('Image', 'head_img_id', 'id');
	}

	public function products()
	{
		return $this->belongsToMany('Product','theme_product','product_id','theme_id');
	}

	public static function getThemeWithProducts($id)
	{
		$theme = self::with('products,topicImg,headImg')->find($id);

		return $theme;
	}
}