<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/22
 * Time: 14:25
 */

namespace app\index\controller;

use app\common\controller\CommController;
use app\index\model\User;
use app\common\controller\PublicFunction;
use think\Session;
use think\Cookie;
use think\Hook;
use think\Db;

class Person extends CommController
{
    public function person(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        //总计
        $profit['total'] = PublicFunction::getTotalProfit($id,'3,4,13',2);
        //分享
        $profit['share'] = PublicFunction::getTotalProfit($id,4,2);
        $profit['friend'] = Db::name('friend') -> where('p_id',$id) -> where('grade',1) -> count();
        $userInfo = $user -> getUserAllByKey($id);
        return $this -> fetch('person/person',['User'=>$userInfo,'Profit'=>$profit]);

    }

    //今日收益
    public function todayProfit(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        //签到
        $profit['sign'] = PublicFunction::getTotalProfit($id,13,2);
        //分享
        $profit['share'] = PublicFunction::getTotalProfit($id,4,2);
        //锁仓
        $profit['lock'] = PublicFunction::getTotalProfit($id,3,2);
        //总计
        $profit['total'] = PublicFunction::getTotalProfit($id,'3,4,13',2);
        return $this -> fetch('person/today',['Profit'=>$profit]);
    }


    //退出登录
    public function loginOut(){
        Hook::listen('CheckAuth',$params);
        Cookie::delete('user');
        return $this ->jsonSuccess('退出当前帐号，请重新登录','/index/login/login');
    }



}