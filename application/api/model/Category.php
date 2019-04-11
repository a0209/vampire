<?php 

namespace app\api\model;

class Category extends BaseModel
{
	protected $hidden = ['delete_time','update_time','create_time'];

	// 外键id在自己的表中就用belongsTo, 在关联表中用hasMany
	public function img()
	{
		return $this->belongsTo('Image', 'topic_img_id', 'id');
	}
}