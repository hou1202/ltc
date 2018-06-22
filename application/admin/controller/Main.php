<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 17:38
 */

namespace app\admin\controller;
use think\Controller;
use think\Session;
use app\common\controller\ReturnJson;

class Main extends Controller
{
    //显示后台主页面
    //通过Session来判断用户是否为正常登录
    //Session为用户登录时所生成
    public function index(){
        if(Session::get('name') || Session::get('power')){
            return $this -> fetch('',['user_name' => Session::get('name')  , 'user_power' => Session::get('power')]);
        }else{
            return ReturnJson::ReturnH('非法的登录方式，请重新登录！','/admin/index');
        }
    }

    public function desktop(){
        return view('desktop');
    }

}