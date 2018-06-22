<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/27
 * Time: 17:08
 */

namespace app\admin\validate;


use think\Validate;

class FavoritesValidate extends Validate
{
    protected $rule = [
        'title' => 'require|max:30',
        'f_id' => 'require|number',
        'f_name' => 'require|max:30',
        'thumbnail' => 'require',
        'sort' => 'number',
    ];

    protected $message = [
        'title.require' => '选品图标题不得为空...',
        'title.max' => '选品图标题最大不得超过10个字...',
        'f_id.require' => '选品库ID不得为空...',
        'f_id.number' => '选品库ID格式不正确，应为数字...',
        'f_name.require' => '选品库名称不得为空...',
        'f_name.max' => '选品库名称不得超过10个字...',
        'thumbnail.require' => '缩略图不得为空...',
        'sort.number' => '排序格式不正确，应为数字...',
    ];

}