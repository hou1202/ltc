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
use app\index\model\Trade as TradeModel;
use app\index\validate\AccountValidate;
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
        $userInfo = $user -> getUserAllByKey($id);
        return $this -> fetch('person/person',['User'=>$userInfo]);

    }



}