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
        'password' => 'require|min:6',
    ];

    protected $message = [
        'phone.require' => '登录手机号码不得不空...',
        'phone.number' => '手机号码格式不正确...',
        'phone.length' => '手机号码格式不正确...',
        'password.require' => '密码不得不空...',
        'password.length' => '密码长度不得少于六位...',
    ];

}