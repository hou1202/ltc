<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/2
 * Time: 10:55
 */
namespace app\index\validate;
use think\Validate;

class UserRegisterValidate extends Validate
{

    protected $rule = [
        'phone' => 'require|length:11|number',
        'pwd_login' => 'require|min:6|alphaNum',
        'sure_pwd_login' => 'confirm:pwd_login',
        'pwd_trade' => 'require|min:6|alphaNum',
        'sure_pwd_trade' => 'confirm:pwd_trade',
        'p_id' => 'require|length:8|alphaNum',
        'code' => 'require|number|length:6',
    ];

    protected $message = [
        'phone.require' => '注册帐户手机号码不得不空...',
        'phone.number' => '手机帐户号码格式不正确...',
        'phone.length' => '手机帐户号码格式不正确...',
        'pwd_login.require' => '帐户登录密码不得不空...',
        'pwd_login.min' => '帐户登录密码长度不得少于六位...',
        'pwd_login.alphaNum' => '帐户登录密码不正确...',
        'sure_pwd_login.confirm' => '帐户登录密码两次输入不一致...',
        'pwd_trade.require' => '帐户交易密码不得不空...',
        'pwd_trade.min' => '帐户交易密码长度不得少于六位...',
        'pwd_trade.alphaNum' => '帐户交易密码不正确...',
        'sure_pwd_trade.confirm' => '帐户交易密码两次输入不一致...',
        'p_id.require' => '邀请码不正确...',
        'p_id.length' => '邀请码不正确...',
        'p_id.alphaNum' => '邀请码不正确...',
        'code.require' => '验证码不得不空...',
        'code.number' => '验证码不正确...',
        'code.length' => '验证码不正确...',
    ];

}