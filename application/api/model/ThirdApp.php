<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/14
 * Time: 16:55
 */

namespace app\api\model;

class ThirdApp extends BaseModel
{
    public static function check($ac, $se)
    {
        $app = self::where('app_id', $ac)->where('app_secret', $se)->find();
        return $app;
    }
}