<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/9
 * Time: 15:38
 */

namespace app\lib\enum;

class ScopeEnum
{
    // scope=16 代表App用户的权限数量
    const User = 16;
    // scope=32 代表CMS（管理员）用户的权限数量
    const Super = 32;
}