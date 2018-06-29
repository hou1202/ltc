<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/20
 * Time: 9:31
 */

namespace app\admin\validate;


use think\Validate;

class ExtractPlatValidate extends Validate
{
    protected $rule = [
        'content' => 'require',
    ];

    protected $message = [
        'content.require' => '提币平台不得为空...',
    ];
}