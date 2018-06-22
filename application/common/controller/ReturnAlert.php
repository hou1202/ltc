<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/2
 * Time: 14:13
 */
namespace app\common\controller;


use think\Controller;

class ReturnAlert extends Controller
{
    static function NoConfirmCueReturn($message){
        echo "<script type='text/javascript'>$('.bomb p').html('<div class=\"bomb\"><p>'+$message+'</p></div>'); $('.bomb').show(300).delay(1500).hide(100);</script>";
        exit;
    }
}