<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 11:44
 */

namespace app\admin\controller;

use app\admin\model\ProductImage;
use app\admin\validate\Paginate;
use app\api\validate\IDMustBePositiveInt;
use think\Controller;
use app\admin\model\Product as ProductModel;
use app\admin\model\Category as CategoryModel;
use app\admin\validate\Product as ProductValidate;

class Product extends Controller
{
    public function manage()
    {
        return $this->fetch('manage');
    }

    public function getList()
    {
        $params = (new Paginate())->goCheck();
        if (array_key_exists('code', $params))
            return $params;
        $data = ProductModel::getList($params);
        return $data;
    }

    public function set($id = 0)
    {
        if (!$id) {
            $title = '新增';
        } else {
            $title = '编辑';
            $data = ProductModel::getInfoById($id);
            $this->assign('data', $data);
        }
        $categoryList = CategoryModel::getAllList();
        $this->assign([
            'title' => $title,
            'categoryList' => $categoryList
        ]);
        return $this->fetch('set');
    }

    public function doSet()
    {
        $params = (new ProductValidate())->goCheck();
        if (array_key_exists('code', $params))
            return $params;
        if (!array_key_exists('id', $params)) {
            // 新增
            $ret = ProductModel::add($params);
        } else {
            // 编辑
            $ret = ProductModel::modify($params);
        }
        return $ret;
    }

    public function delProImg()
    {
        $id = input('post.id/d', 0);
        $ret = ProductImage::del($id);
        return $ret;
    }

    public function del()
    {
        $ids = input('post.id/s');
        $ret = ProductModel::del($ids);
        return $ret;
    }
}