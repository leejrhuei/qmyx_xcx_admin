<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 9:49
 */

use think\Route;

/**
 * 轮播图
 */
Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');

/**
 * 主题
 */
Route::group('api/:version/theme', function () {
    Route::get('', 'api/:version.Theme/getSimpleList');
    Route::get('/:id', 'api/:version.Theme/getComplexOne');
});

/**
 * 产品
 */
Route::group('api/:version/product', function () {
    Route::get('/by_category', 'api/:version.Product/getAllInCategory');
    Route::get('/:id', 'api/:version.Product/getOne', [], ['id' => '\d+']);
    Route::get('/recent', 'api/:version.Product/getRecent');
});

/**
 * 分类
 */
Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');

/**
 * 令牌
 */
Route::group('api/:version/token', function () {
    Route::post('/user', 'api/:version.Token/getToken');
    Route::post('/verify', 'api/:version.Token/verifyToken');
    Route::post('/app', 'api/:version.Token/getAppToken');
});

/**
 * 地址
 */
Route::group('api/:version/address', function () {
    Route::post('', 'api/:version.Address/createOrUpdateAddress');
    Route::get('', 'api/:version.Address/getUserAddress');
});

/**
 * 订单
 */
Route::group('api/:version/order', function () {
    Route::post('', 'api/:version.Order/placeOrder');
    Route::get('/by_user', 'api/:version.Order/getSummaryByUser');
    Route::get('/:id', 'api/:version.Order/getDetail', [], ['id' => '\d+']);
    Route::get('/paginate', 'api/:version.Order/getSummary');
    Route::put('/delivery', 'api/:version.Order/delivery');
});

/**
 * 支付
 */
Route::group('api/:version/pay', function () {
    Route::post('/pre_order', 'api/:version.Pay/getPreOrder');
    Route::post('/notify', 'api/:version.Pay/receiveNotify');
});

Route::group('api/:version/user', function () {
    Route::post('/wx_info', 'api/:version.User/updateWxInfo');
});