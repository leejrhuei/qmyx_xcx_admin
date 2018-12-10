<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 17:28
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createorupdateaddress,getuseraddress']
    ];

    /**
     * 更新或新增用户收获地址
     * @return SuccessMessage
     * @throws UserException
     */
    public function createOrUpdateAddress()
    {
        $validate = new AddressNew();
        $validate->goCheck();
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user)
            throw new UserException();
        $userAddress = $user->address;
        // 根据规则取字段是很有必要的，防止恶意更新非客户端字段
        $data = $validate->getDataByRule(input('post.'));
        if (!$userAddress)
            // 新增
            $user->address()->save($data);
        else
            // 更新
            $user->address->save($data);
        return json(new SuccessMessage(), 201);
    }

    /**
     * 获取用户地址信息
     * @return mixed
     * @throws UserException
     */
    public function getUserAddress()
    {
        $uid = TokenService::getCurrentUid();
        $userAddress = UserAddress::where('user_id', $uid)->find();
        if (!$userAddress)
            throw new UserException([
                'msg' => '用户地址不存在',
                'errorCode' => 60001
            ]);
        return $userAddress;
    }
}