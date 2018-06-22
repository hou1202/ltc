<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/20
 * Time: 9:31
 */

namespace app\admin\validate;


use think\Validate;

class BannerValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:30',
        'img' => 'require|max:255',
        'link' => 'max:255',
    ];

    protected $message = [
        'title.require' => 'Banner标题不得为空...',
        'title.max' => 'Banner标题最大不得超过10个字...',
        'img.require' => 'Banner图内容不得为空...',
        'img.max' => 'Banner图内容最大不得超过255个字符...',
        'link.max' => '链接内容最大不得超过255个字符...',
    ];
}