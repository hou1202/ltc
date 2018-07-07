<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/4
 * Time: 9:20
 */

namespace app\index\controller;
use app\common\controller\CommController;
use app\index\model\User;
use app\index\model\Extract;
use app\index\model\Recharge as RechargeModel;
use app\index\validate\ExtractValidate;
use app\index\validate\RechargeLogValidate;
use app\common\controller\PublicFunction;
use think\Session;
use think\Cookie;
use think\Hook;
use think\Db;

class Recharge extends CommController
{
    //提币申请
    public function extract(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserAllByKey($id);
        //提币控制开关
        $control = true;
        $plat = Db::name('message') -> field('id,content') -> where('type',3) -> where('state',1) -> select();
        return $this -> fetch('recharge/extract',['User'=>$userInfo,'Extract'=>$plat,'Control'=>$control]);
    }

    //提币申请验证
    public function extractCheck(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('提币申请信息有误，请重新操作');
        }
        $data = $this -> request -> post();
        //var_dump($data);die;
        $validate = new ExtractValidate();
        if($validate -> check($data)){
            //验证码验证
            if($data['code'] != Session::get('pull_'.$data['phone'])){
                return $this ->jsonFail('您的验证码信息有误，请重新确认...');
            }
            //交易密码验证
            $user = new User();
            $userInfo = $user -> getUserPartByKey($data['user_id']);
            if(md5($data['pwd_trade']) != $userInfo -> pwd_trade){
                return $this ->jsonFail('交易密码不正确...');
            }

            $data['service_price'] = $data['number']*0.05;
            $data['true_num']= $data['number'] - $data['service_price'];
            $extract = new Extract();
            //更改状态，并返回结果
            if($extract -> saveExtract($data)){
                //减少用户可用资产，并生成记录
                $user ->  setUserAsset($userInfo->id,$data['number'],2);
                PublicFunction::SetCapitalLog($userInfo->id,$data['number'],10);
                //清除短信Session
                Session::delete('pull_'.$data['phone']);
                //更新验证码记录
                Db::table('think_log_verify')->where('phone='.$data['phone'].' AND type=3 AND verify='.$data['code'])->update(['status'=>1, 'e_time'=>date('Y-m-d H:i:s')]);
                return $this ->jsonSuccess('提币申请创建完成，请等待管理员审核','/index/recharge/extractList');
            }else{
                return $this ->jsonFail('提币申请创建失败，请重新操作');
            }

        }else{
            return $this ->jsonFail($validate->getError());
        }

    }

    //提币明细
    public function extractList(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $extract = new Extract();
        $list = $extract -> getExtractById($id);
        return $this -> fetch('recharge/extract_list',['List'=>$list]);
    }

    //提币详情
    public function extractDetail(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $extract = new Extract();
            $getOne = $extract -> getExtractDetailById($id);
            return $this -> fetch('recharge/extract_detail',['getOne'=>$getOne]);
        }
        //提币取消
        if($this -> request ->isPost()){
            $data = $this -> request ->post();
            $extract = new Extract();
            if($extract -> delExtractById($data['id'])){
                return $this ->jsonSuccess('提币申请已取消','/index/recharge/extractList');
            }else{
                return $this ->jsonFail('提币申请取消失败，请重新操作');
            }
        }
    }

    //充币地址
    public function recharge(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserAllByKey($id);
        return $this -> fetch('recharge/recharge',['User'=>$userInfo]);
    }

    //获取充币地址
    public function getRechargeAddress(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('充币地址获取信息有误，请重新获取');
        }
        $data = $this -> request -> post();
        $address = Db::name('message') -> field('id,content') -> where('type',4) -> where('state',1) -> select();
        $data['recharge_address']= $address[rand(0,count($address)-1)]['content'];
        $user = new User();
        //充币地址写入用户数据库
        if(!$user -> saveUserAccount($data['id'],$data)){
            return $this ->jsonFail('充币地址获取信息有误，请重新获取');
        }
        return $data['recharge_address'] ? $this ->jsonSuccess('充币地址获取成功') : $this ->jsonFail(' 充币地址获取失败，请重新获取');;
    }

    //充币明细
    public function rechargeList(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $recharge = new RechargeModel();
        $list = $recharge -> getRechargeById($id);
        return $this -> fetch('recharge/recharge_list',['List'=>$list]);
    }

    //充币详情
    public function rechargeDetail(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $recharge = new RechargeModel();
            $getOne = $recharge -> getRechargeDetailById($id);
            return $this -> fetch('recharge/recharge_detail',['getOne'=>$getOne]);
        }
    }

    //提交充币记录
    public function rechargeLog(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserAllByKey($id);
        $plat = Db::name('message') -> field('id,content') -> where('type',3) -> where('state',1) -> select();
        return $this -> fetch('recharge/recharge_log',['User'=>$userInfo,'Extract'=>$plat]);
    }

    //充币记录验证
    public function logCheck(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('提交充币记录信息有误，请重新操作');
        }
        $data = $this -> request -> post();

        $validate = new RechargeLogValidate();
        if($validate -> check($data)){
            $recharge = new RechargeModel();
            if($recharge -> saveRechargeLog($data)){
                return $this ->jsonSuccess('充币记录创建完成，请等待管理员审核','/index/recharge/rechargeList');
            }else{
                return $this ->jsonFail('充币记录创建失败，请重新操作');
            }

        }else{
            return $this ->jsonFail($validate->getError());
        }
    }

}