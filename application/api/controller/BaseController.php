<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/9
 * Time: 17:36
 */

namespace app\api\controller;

use think\Controller;
use app\api\service\Token as TokenService;

class BaseController extends Controller
{
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }
}