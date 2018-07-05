<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/26
 * Time: 12:01
 */

namespace app\index\controller;
use app\common\controller\CommController;
use app\admin\controller\ReturnJson;
use app\index\model\User;
use app\index\validate\UserLoginValidate;
use app\index\validate\UserRegisterValidate;
use app\index\validate\PwdLoginValidate;
use app\common\controller\PublicFunction;
use think\Session;
use think\Cookie;
use think\Db;

class Login extends CommController
{
    /*
     * @login       用户登录
     * */
    public function login(){
        if(Cookie::has('user')){
            $id = Cookie::get('user');
            $user = new User();
            if($user -> getUserPartByKey($id)){
                $this -> redirect('index/index');
            }
        }else{
            return $this -> fetch('login/login');
        }
    }


    /*
     *@loginCheck  用户登录账户验证
     *  */
    public function loginCheck(){
        if($this -> request ->isPost()){
            $data = $this -> request -> post();
            //var_dump($data);die;
            $userVal = new UserLoginValidate();
            if($userVal -> check($data)){
                $user = new User();
                $result = $user -> loginUserCheck($data['phone'],$data['pwd_login']);
                if($result){
                    if($result['state'] == 0){
                        return $this ->jsonFail('您的帐号处于异常状态，无法登录，请联系管理员...');
                    }else{
                        //设置用户COOKIE，并设置保存时间7天
                        Cookie::set('user',$result['id'],604800);
                        //$this -> redirect('index/index');
                        return $this->jsonSuccess('登录成功','/index/index/index');
                    }

                }else{
                    return $this ->jsonFail('您登录的账户信息有误，请核对后再登录...');
                }

            }else{
                return $this ->jsonFail($userVal->getError());
            }
        }else{
            return $this ->jsonFail('登录信息有误，请重新操作');
        }
    }


    /*
     * @register       用户注册
     * */
    public function register(){
        if ($this -> request -> isPost()) {
            $data = $this -> request -> post();
            //var_dump($data);die;
            $userVal = new UserRegisterValidate();
            if($userVal -> check($data)){
                //判断验证码

                if($data['code'] != Session::get('login_'.$data['phone'])){
                    return $this ->jsonFail('您的验证码信息有误，请重新确认...');
                }

                //实例化用户User  Model
                $user = new User();

                //判断手机号是否注册
                if($user -> getUserPartByKey($data['phone'],'phone')){
                    return $this ->jsonFail('您的帐号已注册，请直接登录...','/index/login/login');
                }

                //判断邀请码是否正确
                if(! $user -> getUserPartByKey($data['p_id'],'share_id')){
                    return $this ->jsonFail('您所填入的邀请码不存在，请重新确认...');
                }

                /*
                 * @写入数据
                 * $data['number']   设置用户会员编号
                 * $data['portrait']    设置用户注册默认头像
                 * $data['share_id']      设置用户注册邀请码
                 * */
                $data['number'] = PublicFunction::SetUserNumber();
                $data['portrait'] = '/static/index/images/head.png';
                $data['share_id'] = PublicFunction::SetShareId(8);
                if($result = $user -> insertUserReturnId($data)){
                    //清除短信Session
                    Session::delete('login_'.$data['phone']);
                    //设置用户COOKIE，并设置保存时间7天
                    Cookie::set('user',$result,604800);
                    //更新验证码记录
                    Db::table('think_log_verify')->where('phone='.$data['phone'].' AND type=0 AND verify='.$data['code'])->update(['status'=>1, 'e_time'=>date('Y-m-d H:i:s')]);

                    //写入分销关系
                    $pShareId=$data['p_id'];
                    $share = array();
                    for($i=0;$i<10;$i++){
                        if($pShareId){
                            $isPUser = $user -> getUserAllByKey($pShareId,'share_id');
                            $share[$i]['user_id'] = $result;
                            $share[$i]['p_id'] = $isPUser['id'];
                            $share[$i]['grade'] = $i+1;
                            $share[$i]['create_time'] = time();
                            $pShareId = $isPUser['p_id'];
                        }else{
                            break;
                        }
                    }
                    $insertShare = Db::name('friend')->insertAll($share);
                    if(!$insertShare){
                        //若分销关系建立失败，撤销创建
                        $user -> where('id',$result) -> delete();
                        return $this ->jsonFail('注册出现了一些小故障，请重新操作...');
                    }



                    return $this->jsonSuccess('恭喜您，帐户已经注册成功','/index/index/index');
                }else{
                    return $this ->jsonFail('注册出现了一些小故障，请重新操作...');
                }

            }else{
                return $this ->jsonFail($userVal->getError());

            }

        }else{
            $invitation = null;
            if($this -> request -> isGet()){
                if(isset($_GET['invitation']) && !empty($_GET['invitation'])){
                    $invitation = $_GET['invitation'];
                }
            }
            return $this -> fetch('login/register',['Inv'=>$invitation]);
        }
        //return $this -> fetch('login/register');
    }


    /*
     * @forget      忘记密码
     * */
    public function forget()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            //var_dump($data);die;
            $validate = new PwdLoginValidate();
            if ($validate->check($data)) {
                //实例化用户User  Model
                $user = new User();

                //判断手机号用户是否存在
                if(! $user -> getUserPartByKey($data['phone'],'phone')){
                    return $this ->jsonFail('该用户不存在，请先注册...','/index/login/register');
                }

                if ($data['code'] != Session::get('epass_' . $data['phone'])) {
                    return $this->jsonFail('您的验证码信息有误，请重新确认...');
                }


                //var_dump($data);die;
                if ($result = $user->setFieldById($data,$data['phone'],'phone')) {
                    //清除短信Session
                    Session::delete('epass_' . $data['phone']);
                    //更新验证码记录
                    Db::table('think_log_verify')->where('phone=' . $data['phone'] . ' AND type=1 AND verify=' . $data['code'])->update(['status' => 1, 'e_time' => date('Y-m-d H:i:s')]);
                    return $this->jsonSuccess('帐户登录密码已修改成功，请登录', '/index/login/login');
                } else {
                    return $this->jsonFail('帐户登录密码修改出错错误，请重新操作...');
                }

            } else {
                return $this->jsonFail($validate->getError());

            }

        } else {

            return $this->fetch('login/forget');
        }
    }
}