<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/3
 * Time: 18:29
 */

namespace app\index\controller;
use app\common\controller\CommController;
use app\index\model\User;
use app\index\validate\AccountValidate;
use app\index\validate\PwdValidate;
use app\index\validate\PwdTradeValidate;
use think\Session;
use think\Cookie;
use think\Hook;
use think\Db;

class Account extends CommController
{
    //编辑帐户资料
    public function personAccount(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserAllByKey($id);
        return $this -> fetch('person/data',['User'=>$userInfo]);
    }

    //帐户资料更新检测
    public function accountCheck(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('修改资料信息有误，请重新操作');
        }
        $data = $this -> request -> post();
        $validate = new AccountValidate();
        if($validate -> check($data)){
            if($data['code'] != Session::get('account_'.$data['phone'])){
                return $this ->jsonFail('您的验证码信息有误，请重新确认...');
            }
            $user = new User();
            //更改状态，并返回结果
            if($user -> saveUserAccount($data['id'],$data)){
                //清除短信Session
                Session::delete('account_'.$data['phone']);
                //更新验证码记录
                Db::table('think_log_verify')->where('phone='.$data['phone'].' AND type=5 AND verify='.$data['code'])->update(['status'=>1, 'e_time'=>date('Y-m-d H:i:s')]);
                return $this ->jsonSuccess('资料更新完成');
            }else{
                return $this ->jsonFail('资料更新失败，请重新操作');
            }

        }else{
            return $this ->jsonFail($validate->getError());
        }

    }


    //修改登录密码
    public function pwdLogin(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserPartByKey($id);
        return $this -> fetch('person/psw',['User'=>$userInfo]);
    }

    //密码修改检测
    public function pwdCheck(){
        if(!$this -> request ->isPost()){
            return $this ->jsonFail('密码修改信息有误，请重新操作');
        }
        $data = $this -> request -> post();
        if(isset($data['pwd_login'])){
            $validate = new PwdValidate();
        }elseif(isset($data['pwd_trade'])){
            $validate = new PwdTradeValidate();
        }else{
            return $this ->jsonFail('密码修改信息有误，请重新操作');
        }
        if($validate -> check($data)){
            if($data['code'] != Session::get('epass_'.$data['phone'])){
                return $this ->jsonFail('您的验证码信息有误，请重新确认...');
            }
            $user = new User();
            //更改状态，并返回结果
            if($user -> saveUserAccount($data['id'],$data)){
                //清除短信Session
                Session::delete('account_'.$data['phone']);
                //更新验证码记录
                Db::table('think_log_verify')->where('phone='.$data['phone'].' AND type=1 AND verify='.$data['code'])->update(['status'=>1, 'e_time'=>date('Y-m-d H:i:s')]);
                return $this ->jsonSuccess('密码修改完成');
            }else{
                return $this ->jsonFail('密码修改失败，请重新操作');
            }

        }else{
            return $this ->jsonFail($validate->getError());
        }
    }

    //修改交易密码
    public function pwdTrade(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $user = new User();
        $userInfo = $user -> getUserPartByKey($id);
        return $this -> fetch('person/psw_trade',['User'=>$userInfo]);
    }

    /*checkBankNum  c验证银行卡号*/
    protected function checkBankNum($card_number){
        $arr_no = str_split($card_number);
        $last_n = $arr_no[count($arr_no)-1];
        krsort($arr_no);
        $i = 1;
        $total = 0;
        foreach ($arr_no as $n){
            if($i%2==0){
                $ix = $n*2;
                if($ix>=10){
                    $nx = 1 + ($ix % 10);
                    $total += $nx;
                }else{
                    $total += $ix;
                }
            }else{
                $total += $n;
            }
            $i++;
        }
        $total -= $last_n;
        $x = 10 - ($total % 10);
        if($x == $last_n){
            return true;
        }else{
            return false;
        }
    }


    /*图像上传*/
    public function uploader(){
        // 获取表单上传文件
        $files = request()->file('');
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if($info){
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $path['name'] = DS . 'uploads/' . $info->getSavename();
            }else{
                // 上传失败获取错误信息
                return $this->error($file->getError()) ;
            }
        }
        echo json_encode($path);
    }
}