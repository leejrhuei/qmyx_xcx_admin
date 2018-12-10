<?php

namespace app\api\model;

class User extends BaseModel
{
    protected $autoWriteTimestamp = true;

    public function address()
    {
        return $this->hasOne('UserAddress', 'user_id', 'id');
    }

    /**
     * 判断openid是否存在
     * @param $openid
     * @return array|false|\PDOStatement|string|\think\Model
     */
    public static function getByOpenID($openid)
    {
        $user = self::where('openid', $openid)->find();
        return $user;
    }
}
