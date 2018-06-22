<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/10
 * Time: 12:06
 */
namespace app\index\validate;


use think\Validate;

class InfoValidate extends Validate
{
    protected $rule = [
        'user_name' => 'require|length:15',
    ];

    protected $message = [
        'uid.require' => '数据信息错误，请重新操作...',
    ];
}