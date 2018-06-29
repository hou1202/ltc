<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/2
 * Time: 10:55
 */
namespace app\index\validate;
use think\Validate;

class UserLoginValidate extends Validate
{

    protected $rule = [
        'phone' => 'require|length:11|number',
        'pwd_login' => 'require|min:6',
    ];

    protected $message = [
        'phone.require' => '登录帐号不得不空...',
        'phone.number' => '登录帐号不正确...',
        'phone.length' => '登录帐号不正确...',
        'pwd_login.require' => '密码不得不空...',
        'pwd_login.length' => '密码长度不得少于六位...',
    ];

}