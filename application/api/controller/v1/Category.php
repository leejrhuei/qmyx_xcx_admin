<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/6
 * Time: 20:21
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\MissException;

class Category
{
    /**
     * 获取所有分类信息
     * url: /api/:version/category/all
     * @return false|static[]
     * @throws MissException
     */
    public function getAllCategories()
    {
        $data = CategoryModel::all([], 'img');
        if (empty($data))
            throw new MissException([
                'msg' => '还没有任何类目',
                'errorCode' => 50000
            ]);
        return $data;
    }
}