<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 10:59
 */

namespace app\admin\model;

class Admin extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public static function add($postData)
    {
        $postData['account'] = trim($postData['account']);
        $count = self::where('account', $postData['account'])->count();
        if ($count) {
            $ret = [
                'code' => 1001,
                'msg' => '账号已存在'
            ];
            return $ret;
        }
        $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
        $bool = self::create($postData);
        if (!$bool)
            $ret = [
                'code' => 1001,
                'msg' => '新增失败'
            ];
        else
            $ret = [
                'code' => 0,
                'msg' => '新增成功'
            ];
        return $ret;
    }

    public static function check($postData)
    {
        $postData['account'] = trim($postData['account']);
        $data = self::where(['account' => $postData['account'], 'delete_time' => null])->find();
        if (!$data) {
            $ret = [
                'code' => 1001,
                'msg' => '账号不存在',
            ];
            return $ret;
        }
        if (!password_verify($postData['password'], $data->password)) {
            $ret = [
                'code' => 1001,
                'msg' => '密码不正确',
            ];
            return $ret;
        }
        $ret = [
            'code' => 0,
            'msg' => '登陆成功',
        ];
        return $ret;
    }
}