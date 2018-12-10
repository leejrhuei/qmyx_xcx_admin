<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/18
 * Time: 20:57
 */

namespace app\api\controller\v1;

use app\api\service\Token as TokenService;
use app\api\model\User as UserModel;

class User
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'updatewxinfo']
    ];

    public function updateWxInfo($userData)
    {
        $uid = TokenService::getCurrentUid();
        $userData = json_decode($userData, true);
        $userData['id'] = $uid;
        UserModel::update($userData);
    }
}