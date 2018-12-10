<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 20:09
 */

namespace app\admin\model;

class Banner extends BaseModel
{
    protected $hidden = ['update_time', 'delete_time'];

    protected $autoWriteTimestamp = true;
}