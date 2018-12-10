<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/17
 * Time: 19:41
 */

namespace app\admin\model;

class Image extends BaseModel
{
    protected $hidden = ['from', 'create_time', 'update_time', 'delete_time'];

    protected $autoWriteTimestamp = true;
}