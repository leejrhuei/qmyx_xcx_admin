<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 17:59
 */

namespace app\admin\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    /**
     * 单图上传
     * @return string
     */
    public function upload()
    {
        $pathName = Request::instance()->post('pathName');
        $responseArr = [];
        $file = request()->file('file');
        //移动到框架应用根目录/runtime/uploads/$pathName目录下
        $savePath = ROOT_PATH . 'runtime' . DS . 'uploads' . DS . $pathName;
        $info = $file->validate(['ext' => 'jpg,png,gif,bmp,jpeg'])->move($savePath, '');
        if ($info) {
            //成功上传后 获取上传信息
            $responseArr['code'] = 0;
            $responseArr['msg'] = '上传成功';
            $responseArr['src'] = $savePath . DS . $info->getSaveName();
        } else {
            //上传失败获取错误信息
            $responseArr['code'] = 1;
            $responseArr['msg'] = $file->getError();
        }
        return json_encode($responseArr);
    }
}