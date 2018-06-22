<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/13
 * Time: 16:02
 */
namespace app\admin\controller;


use app\admin\validate\UserAddValidate;
use app\admin\validate\UserUpdateValidate;
use app\common\controller\CommController;
use app\common\controller\ReturnJson;
use app\admin\model\User as UserModel;
use app\common\controller\PublicFunction;
use think\Db;

/*
 * 用户信息管理
 **/
class User extends CommController
{
    /*
     * @ userList   用户列表
     * */
    public function userList(){
        $user = new UserModel();
        $userCount = $user -> getCountUser();
        $userList = $user -> getUserForList();
        return $userList -> items() ? view('user_list',['List' => $userList , 'Count' => $userCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    /*
     * @ userAdd    新建用户
     * */
    public function userAdd(){
        if($this->request->isPost()){
            $data = $this -> request -> post();
            $validate = new UserAddValidate();
            if($validate -> check($data)){
                $user = new UserModel();
                $isUser = $user -> getOneUserInfoById($data['p_id'],'share_id');
                if(!$isUser){
                    return ReturnJson::ReturnJ("您所提交的邀请用户分享ID不正确，请确认后再提交 ","false");
                }
                $data['number'] = PublicFunction::SetUserNumber();
                $data['share_id'] = PublicFunction::SetShareId(8);
                $data['pwd_login'] = md5($data['pwd_login']);
                $data['pwd_trade'] = md5($data['pwd_trade']);
                $data['create_time'] = time();
                $data['update_time'] = time();
                $returnKey = $user -> insertUserReturnId($data);

                //若有可用资金，写入资金变更记录
                if($data['asset_avali']){
                    $logId = $this ->setCapitalInfo($returnKey,$data['asset_avali']);
                }

                //写入分销关系
                if($returnKey){
                    $pShareId=$data['p_id'];
                    $share = array();
                    for($i=0;$i<10;$i++){
                        if($pShareId){
                            $isPUser = $user -> getOneUserInfoById($pShareId,'share_id');
                            $share[$i]['user_id'] = $returnKey;
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
                        $user -> where('id',$returnKey) -> delete();
                        return ReturnJson::ReturnJ("用户数据创建失败，请重新提交...","false");
                    }

                    return ReturnJson::ReturnJ("用户数据创建成功...","success","/user/userList");
                }else{
                    return ReturnJson::ReturnJ("用户数据创建失败，请重新提交...","false");
                }

                //return $user->allowField(true)->save($data) ? ReturnJson::ReturnJ("用户数据创建成功...","success","/user/userList") : ReturnJson::ReturnJ("用户数据创建失败，请重新提交...","false");
            }else{
                return ReturnJson::ReturnJ($validate -> getError(),"false");
            }
        }else{
            return view('user_add');
        }
    }

    /*
     * @ userUpdate    修改用户
     * */
    public function userUpdate(){
        //修改提交信息
        if($this -> request -> isPost()){
            $data = $this -> request -> Post();
            //var_dump($data);die;
            if(isset($data['id']) && empty($data['id'])){
                return ReturnJson::ReturnJ('无效的数据操作...','false','/user/userList');
            }
            $validate = new UserUpdateValidate();
            if($validate -> check($data)){
                $user = new UserModel();

                if(empty($data['pwd_login'])){unset($data['pwd_login']);}
                if(empty($data['pwd_trade'])){unset($data['pwd_trade']);}
                if(empty($data['asset_avali'])){
                    unset($data['asset_avali']);
                }else{
                    $userInfo = $user -> getOneUserInfoById($data['id']);
                    //写入资金变更记录
                    $logId = $this ->setCapitalInfo($data['id'],$data['asset_avali']);
                    $data['asset_avali'] = $userInfo -> asset_avali + $data['asset_avali'];
                }

                if($user -> save($data,['id' => $data['id']])){
                    return ReturnJson::ReturnJ('数据更新成功...','success','/user/userList');
                }else{
                    if(isset($logId)){
                        Db::name('capital_log')->where('id',$logId) -> delete();
                    }

                    return ReturnJson::ReturnJ('数据更新失败，请重新操作...','false');
                }

            }
            return ReturnJson::ReturnJ($validate -> getError(),'false');
        }

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $user = new UserModel();
            $getOne = $user -> getOneUserInfoById($id);
            return $getOne ?  view('user_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的用户信息...","#/user/user_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }


    /*
    * @ userCat    删除用户
    * */
    public function userDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $user = new UserModel();
            return $user -> where('id',$id) -> delete() ? ReturnJson::ReturnJ("已成功删除此用户信息...") : ReturnJson::ReturnJ($user -> getError(),"false");
        }
        return ReturnJson::ReturnJ("非法的数据提交信息!","false");
    }

    /*
    * @ userSearch    搜索用户
    * */
    public function userSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $user = new UserModel();
                $List = $user->getSearchUserByKeyword($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('user_list' , ['List' => $List , 'Count' => $user->getCountSearchUserByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }

    /*
    * @ userSort    按排序信息查看用户
    * */
    public function userSort(){
        //获取、展示修改信息
        if(isset($_GET['key']) && !empty($_GET['key'])){
            $data = $this -> request -> get();
            $user = new UserModel();
            $userCount = $user -> getCountUser();
            $userList = $user -> getUserForList($data['key']);
            return $userList -> items() ? view('user_list',['List' => $userList , 'Count' => $userCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
        }else{
            ReturnJson::ReturnA("无效的操作...");
        }

    }


    /*
     * setCapitalInfo       设置后台修改用户可用资产记录生成信息
     * $id                  用户ID
     * $capital             变动资产金额
     * */
    private function setCapitalInfo($id,$capital){
        $way = 1;
        if($capital<0){
            $way = 2;
        }
        $log_Id = PublicFunction::SetCapitalLog($id,$capital,$way);
        if($log_Id){
            return $log_Id;
        }else{
            return false;
        }

    }








}