<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 14:45
 */

namespace app\admin\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('index');
    }

    public function console()
    {
        return $this->fetch('console');
    }
}