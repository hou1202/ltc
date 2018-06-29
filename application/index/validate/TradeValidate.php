<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/2
 * Time: 10:55
 */
namespace app\index\validate;
use think\Validate;

class TradeValidate extends Validate
{

    protected $rule = [
        'number' => 'require|number|integer|egt:1',
        'pwd_trade' => 'require|min:6',
        'code' => 'require|length:6|number|integer',
    ];

    protected $message = [
        'number.require' => '交易购买数量不得不空...',
        'number.number' => '交易购买数量信息有误...',
        'number.integer' => '交易购买数量信息有误...',
        'number.egt' => '交易购买数量信息有误...',
        'pwd_trade.require' => '交易密码不正确...',
        'pwd_trade.min' => '交易密码不正确...',
        'code.require' => '验证码信息不正确...',
        'code.length' => '验证码信息不正确...',
        'code.number' => '验证码信息不正确...',
        'code.integer' => '验证码信息不正确...',
    ];

}