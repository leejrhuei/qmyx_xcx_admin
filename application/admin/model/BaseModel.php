<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 17:24
 */

namespace app\admin\model;

use think\Model;

class BaseModel extends Model
{
    public function prefixImgUrl($value, $data)
    {
        if ($data['from'] == 1)
            return config('setting.img_prefix') . $value;
        return $value;
    }
}