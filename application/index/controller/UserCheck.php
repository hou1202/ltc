<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/26
 * Time: 16:21
 */

namespace app\index\controller;

use think\Controller;
use think\Cookie;

class UserCheck extends Controller
{
    //类里面引入jump类
    //use \traits\controller\Jump;

    //绑定到CheckAuth标签，可以用于检测Session/Cookie以用来判断用户是否登录
    public function run(&$params){
        $uid = Cookie::get('user');
        if(!isset($uid)){
            $uid = "";
        }
        if($uid == null || $uid == "" || $uid == "null" || $uid == 0){
            $this -> redirect('login/attestation');
        }
    }
}