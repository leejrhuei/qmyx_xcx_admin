<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 9:53
 */

use think\Route;

/**
 * 登陆
 */
Route::group('login', function () {
    Route::get('', 'admin/Login/login');
    Route::post('', 'admin/Login/doLogin');
});

/**
 * 控制台
 */
Route::get('/', 'admin/Index/index');
Route::get('/console', 'admin/Index/console');

/**
 * 轮播图
 */
Route::group('banner', function () {
    Route::get('', 'admin/Banner/manage');
    Route::get('/set', 'admin/Banner/set');
    Route::post('/doSet', 'admin/Banner/doSet');
    Route::get('/list', 'admin/Banner/getList');
    Route::get('/set/:id', 'admin/Banner/set', [], ['id' => '\d+']);
    Route::post('/del', 'admin/Banner/del');
});

/**
 * 上传
 */
Route::post('/upload', 'admin/Common/upload');
Route::post('/uploads', 'admin/Common/uploads');

/**
 * 用户
 */
Route::group('user', function () {
    Route::get('', 'admin/User/manage');
    Route::get('/list', 'admin/User/getList');
});

/**
 * 分类
 */
Route::group('category', function () {
    Route::get('', 'admin/Category/manage');
    Route::get('/list', 'admin/Category/getList');
    Route::get('/set', 'admin/Category/set');
    Route::post('/doSet', 'admin/Category/doSet');
    Route::get('/set/:id', 'admin/Category/set', [], ['id' => '\d+']);
    Route::post('/del', 'admin/Category/del');
});

/**
 * 商品
 */
Route::group('product', function () {
    Route::get('', 'admin/Product/manage');
    Route::get('/list', 'admin/Product/getList');
    Route::get('/set', 'admin/Product/set');
    Route::post('/doSet', 'admin/Product/doSet');
    Route::get('/set/:id', 'admin/Product/set', [], ['id' => '\d+']);
    Route::post('/delProImg', 'admin/Product/delProImg');
    Route::post('/del', 'admin/Product/del');
});

/**
 * 订单
 */
Route::group('order', function () {
    Route::get('', 'admin/Order/manage');
    Route::get('/list', 'admin/Order/getList');
});