<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 14:24
 */

namespace app\index\controller;
use app\admin\controller\ReturnJson;
use app\common\controller\CommController;
use app\common\controller\PublicFunction;
use app\index\model\User;
use app\index\model\News;
use app\index\model\Lock;
use think\Session;
use think\Cookie;
use think\Hook;
use think\Db;

class Index extends CommController
{
    const SIGN_BONUS = 0.01;        //签到奖励金额


    public function index(){
        //检测用户登录状态
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $result = $user -> getUserAllByKey($id);
        if(!$result || $result['state'] == 0){
            return $this ->jsonFail('未查找到你们账户信息，请重新确认账户，或注册...','login/regist');
            //$this -> redirect('login/login');
        }

        //取数据
        $price = Db::name('price')->field('price')->order('id DESC')->find();
        $news = new News();
        $newsList = $news -> getNewsForIndex($id);
        $lock = new Lock();
        $sort = $lock -> userLockSort();
        return $this -> fetch('index/index',['User'=>$result,'News'=>$newsList,'Price'=>$price,'Sort'=>$sort]);
    }

    public function signCheck(){
        if($this->request->isPost()){
            $data=$this->request->post();
            if(isset($data['sign']) && $data['sign'] == true && isset($data['id']) && !empty($data['id'])){
                $user = new User();
                $userInfo = $user -> getUserAllByKey($data['id']);
                if(!$userInfo){
                    return $this ->jsonFail('您的帐户信息有误，建议您退出后再登录，重新签到...');
                }
                //判断今天是否已经签到过
                if($userInfo->sign_time > strtotime(date('Y-m-d',time()))){
                    return $this ->jsonFail('您今天已经完成签到，请明天再来签到...');
                }
                //生成用户签到记录
                $new['sign_time']=time();
                //生成用户签到奖励
                $new['asset_avali'] = $userInfo -> asset_avali + self::SIGN_BONUS;
                //var_dump($new);die;
                //写入数据
                $user -> setFieldById($new,$userInfo->id);
                //生成资金变更记录
                PublicFunction::SetCapitalLog($userInfo->id,self::SIGN_BONUS,13);
                return $this ->jsonSuccess('恭喜您今日签到成功...');

            }else{
                return $this ->jsonFail('签到失败，重新签到...');
            }
        }
    }





    //生成用户访问记录信息
    public function visitRecordIp(){
        if($this->request->isPost()){

            $data=$this->request->post();
            $request = $this->request->instance();
            $data['ip'] = $request -> ip();
            $data['address'] = $this -> peggingIp($request -> ip());
            $data['create_time'] = time();
            $visit = Db::name('visit')->insert($data);
            if($visit){
                return $this->jsonSuccess(true);
            }else{
                return $this->jsonFail(false);
            }

        }

    }

    protected function peggingIp($ip){
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip='.$ip);
        if(empty($res)){ return $address='未知'; }
        $jsonMatches = array();
        preg_match('#{.+?}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){ return $address='未知'; }
        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1){
            $json['ip'] = $ip;
            unset($json['ret']);
        }else{
            return false;
        }
        $address = $json['country'].'.'.$json['province'].'.'.$json['city'];
        return $address;
    }






}