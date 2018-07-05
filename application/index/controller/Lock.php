<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/27
 * Time: 13:26
 */

namespace app\index\controller;
use app\common\controller\ReturnJson;
use app\common\controller\CommController;
use app\index\model\User;
use app\index\model\Lock as LockModel;
use app\index\validate\LockValidate;
use app\common\controller\PublicFunction;
use think\Session;
use think\Cookie;
use think\Hook;
use think\Db;


class Lock extends CommController
{
    //锁仓计划页面
    public function lock(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserPartByKey($id);
        //锁仓计划
        $plan = Db::name('plan') -> where('state',1) -> select();
        //当前有效锁仓计划剩余数量
        $surplus = $this -> surplusLock();

        //LTC价格
        $price = Db::name('price') -> field('price') -> select();
        $strPrice = null;
        foreach($price as $pr){
            $strPrice .= $pr['price'].',';
        }
        $strPrice = substr($strPrice,0,strlen($strPrice)-1);
        return $this -> fetch('lock/lock',['User'=>$userInfo,'Plan'=>$plan,'Surplus'=>$surplus,'Price'=>$strPrice]);
    }


    /*
     * @surplusLock         计算当前进行锁仓计划剩余份额数
     * $plan                锁仓计划资源结果集
     * $todayLock           今日锁仓列表资源结果集
     * @return              剩余份额数
     * */
    protected function surplusLock(){
        $plan = Db::name('plan') -> where('state',1) -> select();
        $lock = new LockModel();
        $todayLock = $lock -> getTodayLock();
        $surplus = 0;
        foreach($plan as &$p) {
            if (date('H', $p['start_time']) <= date('H', time()) && date('H', $p['end_time']) > date('H', time())){
                $surplus = $p['number'];
                foreach ($todayLock as $t) {
                    if ($p['day'] == $t['lock_time']) {
                        $surplus = $surplus - $t['number'];
                    }
                }
            }
        }
        return $surplus;
    }


    /*
     * @lockPlan            创建锁仓计划
     * */
    public function lockPlan(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('锁仓计划信息有误，请重新操作');
        }
        $data = $this -> request -> post();
        $validate = new LockValidate();
        if($validate -> check($data)){
            $id = Cookie::get('user');
            $user = new User();
            $userInfo = $user -> getUserPartByKey($id);
            //当前有效锁仓计划剩余数量
            $surplus = $this -> surplusLock();
            if($data['number'] > $userInfo->asset_avali || $data['number'] > $surplus){
                return $this ->jsonFail('锁仓计划数量为小于50和可用资产的正整数...');
            }
            if(md5($data['pwd_trade']) != $userInfo -> pwd_trade){
                return $this ->jsonFail('交易密码不正确...');
            }
            $plan = Db::name('plan') -> where('id',$data['plan']) -> find();
            if(!$plan){
                return $this ->jsonFail('锁仓计划类型信息有误1...');
            }
            $lock = new LockModel();
            $data['user_id'] = $userInfo -> id;
            $data['lock_time'] = $plan['day'];
            $data['lock_ratio'] = $plan['ratio'];
            //减少用户可用资产，并生成记录
            $user ->  setUserAsset($userInfo->id,$data['number'],2);
            PublicFunction::SetCapitalLog($userInfo->id,$data['number'],8);
            //增加用户固定资产
            $user ->  setUserAsset($userInfo->id,$data['number'],1,'asset_fixed');
            //写入锁仓数据，并返回结果
             return $lock -> saveLock($data) ?  $this ->jsonSuccess('您的锁仓计划创建成功...','/index/lock/lockList') : $this ->jsonFail('锁仓计划出现问题，请重新操作...');

        }else{
            return $this ->jsonFail($validate->getError());
        }

    }

    //锁仓明细
    public function lockList(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $lock = new LockModel();
        $list = $lock -> getAppointLock($id);
        $Capital['count'] = Db::name('capital_log')->where('user_id',$id)->where('way',3)->sum('capital');
        $today_start = strtotime(date('Y-m-d',time()));
        $today_end = strtotime(date('Y-m-d',time()).' 23:59:59');
        $Capital['today'] = Db::name('capital_log')->where('user_id',$id)
                                                    ->where('way',3)
                                                    ->where('create_time','>',$today_start)
                                                    -> where('create_time','<',$today_end)
                                                    ->sum('capital');
        return $this -> fetch('lock/lock_list',['List'=>$list,'Capital'=>$Capital]);
    }

    //最新成交
    public function lockDetail(){

        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $lock = new LockModel();
            $getOne = $lock -> getOneLockInfo($id);
            //var_dump($getOne->create_time);die;
            $other['end_time'] = date('Y-m-d',$getOne -> create_time[1]+$getOne -> lock_time*86400);
            $other['process'] = intval((time()-$getOne -> create_time[1])/86400)+1;
            if($getOne -> create_time[0] == date('Y-m-d',time())){
                $other['process'] = 0;
            }
            if($other['process'] > $getOne -> lock_time){
                $other['process'] = $getOne -> lock_time;
            }
            //如果为系统中断，赋值
            if($getOne -> is_break){
                $other['end_time'] = date('Y-m-d',$getOne -> is_break);
                $other['process'] = intval(($getOne -> is_break - $getOne -> create_time[1])/86400);
            }
            return $this -> fetch('lock/lock_detail',["getOne"=>$getOne,"Other"=>$other]);
        }else{
            return ReturnJson::ReturnA("无效的修改操作...");
        }
    }

    /*//最新成交
    public function newDeal(){
        return $this -> fetch('lock/new_deal');
    }*/

}