<?php
/**
 * Created by PhpStorm.
 * User: linxiao
 * Date: 2018/7/7
 * Time: 11:31
 */

return [
    'app_id' => 'wxb102baf5685ec172',
    'app_secret' => 'cd1abbdb74c2677ebe124e46f5d19f12',
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s"
];