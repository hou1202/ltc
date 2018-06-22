<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 16:56
 */
namespace app\admin\controller;
use think\captcha\Captcha;
use think\Controller;
use think\Session;
use app\admin\model\Manager as ManagerModel;
use app\common\controller\ReturnJson;

class Index extends Controller
{
    public function index(){
        $captcha = new Captcha();

        return $this -> fetch('',[$captcha->entry()]);
    }

    public function login(){
        if($this -> request -> isPost()){
            $post_data = array();
            $post_data = $this -> request -> post();
            $captcha = new Captcha();
            $validata = $captcha -> check($post_data['code']);
            $validata = 1;
            if($validata){
                $manager = new ManagerModel();
                //$returnManager = $manager->login($post_data['account'],$post_data['password']);
                $returnManager = $manager::get(['account' => $post_data['account']]);
                //登录数据验证判断
                if(!$returnManager){
                    return ReturnJson::ReturnA('查无此管理员帐户，请重新登录！');
                }elseif($returnManager -> password != md5($post_data['password'])){
                    return ReturnJson::ReturnA('管理员帐户密码错误，请重新登录！');
                }elseif(empty($returnManager -> state)){
                    return ReturnJson::ReturnA('管理员帐户被冻结，请联系Admin！');
                }else{
                    //登录成功操作
                    Session::set('name' , $returnManager -> name);
                    Session::set('power' , $returnManager -> power);

                    $up_date=[
                         'login_count' => $returnManager -> login_count +1,
                         'last_time' => time(),
                         'last_ip' => $_SERVER['REMOTE_ADDR']
                    ];
                    $manager::update($up_date,['id' => $returnManager -> id]);
                    //return $this -> fetch('main/index',['user_name' => $returnManager -> name  , 'user_power' => $returnManager -> power]);
                    //URL重定向
                    $this -> redirect('main/index');
                }

            }else{
                return ReturnJson::ReturnA('用户登录验证码错误，请重新登录！');
            }


        }
    }

    public function loginOut(){
        Session::clear();
        return ReturnJson::ReturnJ('退出登录成功！','success','http://web.zhe.com/admin',true);
    }
}