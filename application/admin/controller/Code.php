<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 17:42
 */

namespace app\admin\controller;
use think\captcha\Captcha;
use think\Controller;

class Code extends Controller
{
    static function ReturnCode(){
        $config = [
            'imageH' => 30,
            'imageW' => 100,
            'useNoise' => false,
            'useCurve' => false,
            'length' => 4,
            'fontSize' => 15,
            'bg' => [255,255,255],
        ];
        $captcha = new Captcha($config);
        return $captcha ->entry();
    }

}