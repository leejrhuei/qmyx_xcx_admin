<?php

namespace app\api\model;

class UserAddress extends BaseModel
{
    protected $hidden = ['id', 'user_id', 'update_time', 'delete_time'];
}
