<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/20
 * Time: 9:31
 */

namespace app\admin\validate;


use think\Validate;

class RechargeAddressValidate extends Validate
{
    protected $rule = [
        'content' => 'require|alphaNum',
    ];

    protected $message = [
        'content.require' => '允币地址不得为空...',
        'content.alphaNum' => '允币地址格式不正确...',
    ];
}