<?php

namespace app\api\controller\v1;

use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;
use app\api\validate\IDMustBePostiveInt;

class Product
{
	// 新品推荐
	public function getRecent($count=15)
	{
		(new Count())->goCheck();
		$products = ProductModel::getMostRecent($count);

		if($products->isEmpty()){
			throw new ProductException();
		}
		$products = $products->hidden(['summary']);

		return $products;
	}

	//获取相应分类下的商品
	public function getAllInCategory($id)
	{
		(new IDMustBePostiveInt())->goCheck();
		$products = ProductModel::getProductsByCategoryID($id);

		if($products->isEmpty()){
			throw new ProductException();
		}
		$products = $products->hidden(['summary']);

		return $products;
	}

	//商品详情
	public function getOne($id)
	{
		(new IDMustBePostiveInt())->gocheck();
		$product = ProductModel::getProductDetail($id);

		if(!$product){
			throw new ProductException();
		}

		return $product;
	}
}