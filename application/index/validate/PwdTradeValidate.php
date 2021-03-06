<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/3
 * Time: 17:53
 */

namespace app\index\validate;
use think\Validate;

class PwdTradeValidate extends Validate
{
    protected $rule = [
        'id' => 'require|number|integer|egt:1',
        'phone' => 'require|length:11|number',
        'pwd_trade' => 'require|min:6',
        'sure_pwd' => 'confirm:pwd_trade',
        'code' => 'require|length:6|number|integer',
    ];

    protected $message = [
        'id.require' => '帐户资料修改信息有误',
        'id.number' => '帐户资料修改信息有误',
        'id.integer' => '帐户资料修改信息有误',
        'id.egt' => '帐户资料修改信息有误',

        'phone.require' => '帐户资料修改信息有误',
        'phone.number' => '帐户资料修改信息有误',
        'phone.length' => '帐户资料修改信息有误',

        'pwd_trade.require' => '帐户交易密码不得不空',
        'pwd_trade.length' => '帐户交易密码长度不得少于六位',

        'sure_pwd.confirm' => '帐户交易密码两次输入不一致',

        'code.require' => '验证码信息不正确',
        'code.length' => '验证码信息不正确',
        'code.number' => '验证码信息不正确',
        'code.integer' => '验证码信息不正确',
    ];
}