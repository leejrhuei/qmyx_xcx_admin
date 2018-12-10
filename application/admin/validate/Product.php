<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 16:48
 */

namespace app\admin\validate;

class Product extends BaseValidate
{
    protected $rule = [
        'category_id' => 'isPositiveInteger',
        'name' => 'require',
        'main_img_url' => 'require',
        'pics' => 'require|array',
        'price' => 'require|float',
        'stock' => 'isPositiveInteger'
    ];

    protected $message = [
        'category_id.isPositiveInteger' => '请选择正确的分类',
        'name.require' => '名称必填',
        'main_img_url.require' => '封面图必传',
        'pics.require' => '详情图必传',
        'pics.array' => '详情图必传1',
        'price.require' => '价格必填',
        'price.float' => '请填写正确的价格',
        'stock.require' => '库存必填',
        'stock.isPositiveInteger' => '请填写正确的库存'
    ];
}