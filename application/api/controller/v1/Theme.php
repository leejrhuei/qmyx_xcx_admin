<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/6
 * Time: 16:09
 */

namespace app\api\controller\v1;

use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePositiveInt;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * 根据id字符串获取主题信息
     * url: /api/:version/theme?ids=1,2,3
     * @param string $ids
     * @return array|false|\PDOStatement|string|\think\Collection
     * @throws ThemeException
     */
    public function getSimpleList($ids = '')
    {
        $validate = new IDCollection();
        $validate->goCheck();
        $ids = explode(',', $ids);
        $data = ThemeModel::with('topicImg,headImg')->select($ids);
        if (!$data) {
            throw new ThemeException();
        }
        return $data;
    }

    /**
     * 根据id获取主题信息
     * url: /api/:version/theme/:id
     * @param $id
     * @return array
     * @throws ThemeException
     */
    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $data = ThemeModel::getThemeWithProducts($id);
        if (!$data)
            throw new ThemeException();
        return $data;
    }
}