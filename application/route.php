<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

//首页轮播
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

//首页精选主题
Route::get('api/:version/theme', 'api/:version.Theme/getSimpleList');

//主题列表
Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');


//分类下的商品
Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');

//商品详情
Route::get('api/:version/product/:id', 'api/:version.Product/getOne',[], ['id'=>'\d+']);

//新品推荐
Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');


/*
	Route::group('api/:version/product',function(){
		Route::get('/by_category', 'api/:version.Product/getAllInCategory');
		Route::get('/:id', 'api/:version.Product/getOne',[], ['id'=>'\d+']);
		Route::get('/recent', 'api/:version.Product/getRecent');
	});
*/

//分类
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');


//获取token令牌
Route::post('api/:version/token/user', 'api/:version.Token/getToken');

//地址
Route::post('api/:version/address', 'api/:version.Address/createOrUpdateAddress');

//订单
Route::post('api/:version/order', 'api/:vesion.Order/placeOrder');

//支付-预订单
Route::post('api/:version/pay/pre_order', 'api/:version.Pay/getPreOrder');

