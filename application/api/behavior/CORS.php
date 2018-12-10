<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/16
 * Time: 11:55
 */

namespace app\api\behavior;

class CORS
{
    public function appInit(&$params)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token, Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET');
        if (request()->isOptions()) {
            exit();
        }
    }
}