<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/3
 * Time: 11:48
 */

namespace app\index\validate;
use think\Validate;

class TradeConfirm extends Validate
{
    protected $rule = [
        'id' => 'require|number|integer|egt:1',
        'pwd_trade' => 'require|min:6',
    ];

    protected $message = [
        'id.require' => '交易收款确认信息有误',
        'id.number' => '交易收款确认信息有误',
        'id.integer' => '交易收款确认信息有误',
        'id.egt' => '交易收款确认信息有误',
        'pwd_trade.require' => '交易密码不正确',
        'pwd_trade.min' => '交易密码不正确',
    ];
}