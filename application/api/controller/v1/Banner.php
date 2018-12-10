<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/5
 * Time: 10:40
 */

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\api\model\Banner as BannerModel;
use app\lib\exception\MissException;

class Banner
{
    /**
     * 根据id获取banner信息
     * url: /api/:version/carousel/:id
     * @param $id
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws MissException
     */
    public function getBanner($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $data = BannerModel::getBannerById($id);
        if (!$data)
            throw new MissException([
                'msg' => '请求banner不存在',
                'errorCode' => 40000
            ]);
        return $data;
    }
}