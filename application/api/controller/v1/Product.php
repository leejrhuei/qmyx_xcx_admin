<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/6
 * Time: 19:20
 */

namespace app\api\controller\v1;

use app\api\model\Product as ProductModel;
use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ProductException;
use app\lib\exception\ThemeException;

class Product
{
    /**
     * 获取某分类下全部商品(不分页）
     * url: /api/:version/product/by_category?id=:category_id
     * @param int $id
     * @return mixed
     */
    public function getAllInCategory($id = -1)
    {
        (new IDMustBePositiveInt())->goCheck();
        $data = ProductModel::getProductsByCategoryID($id, false);
        if (!$data)
            throw new ThemeException();
        $data = collection($data)->hidden(['summary'])->toArray();
        return $data;
    }

    /**
     * 获取指定数量的最新商品信息
     * url: /api/:version/product/recent?count=:count
     * @param int $count
     * @return mixed
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        $data = ProductModel::getMostRecent($count);
        if (!$data)
            throw new ProductException();
        return $data;
    }

    /**
     * 获取指定商品信息
     * url: /api/:version/product/:id
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws ProductException
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $data = ProductModel::getProductDetail($id);
        if (!$data)
            throw new ProductException();
        return $data;
    }

    public function deleteOne($id)
    {

    }
}