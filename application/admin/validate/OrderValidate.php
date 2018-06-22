<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/20
 * Time: 9:31
 */

namespace app\admin\validate;


use think\Validate;

class OrderValidate extends Validate
{
    protected $rule = [
        'state' => 'require|number',
    ];

    protected $message = [
        'state.require' => '数据信息有误，请重新操作...',
        'state.number' => '数据信息有误，请重新操作...',
    ];
}