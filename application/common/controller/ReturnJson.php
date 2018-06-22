<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 16:39
 */
namespace app\common\controller;
use think\controller;

class ReturnJson extends Controller
{
    //静态JSON返回数据
    static function ReturnJ($message,$state="success",$referer="",$refresh=true){
        return json([
            'referer' => $referer,
            'refresh' => $refresh,
            'state' => $state,
            'message' => $message,
        ]);
    }

    static function ReturnA($message){
        //提示信息，回退上一页面
        echo "<script type='text/javascript'>alert('$message');history.back();</script>";
        exit;

    }

    static function ReturnH($message,$url){
        //提示信息，跳转指定页面
        echo "<script type='text/javascript'>alert('$message');location.href='$url'</script>";
        exit;
    }

    //$returnHref()无提示信息跳转页面
    //$urll默认为空
    //$url为空，则回退上一页面
    //$url不为空，则跳转指定页面
    static function returnHref($url=''){
        if(empty($url)){
            echo "<script type='text/javascript'>history.back();</script>";
            exit;
        }else{
            echo "<script type='text/javascript'>location.href='$url'</script>";
            exit;
        }
    }

}