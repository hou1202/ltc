<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/14
 * Time: 15:27
 */

namespace app\admin\controller;

use app\common\controller\CommController;
use app\common\controller\PublicFunction;
use app\common\controller\ReturnJson;
use app\admin\model\Lock as LockModel;
use app\admin\model\User;
use app\common\model\Friend;

//锁仓管理
class Lock extends CommController
{

    /*
     * @ lockList   锁仓列表
     * */
    public function lockList(){
        $lock = new LockModel();
        $lockCount = $lock -> getCountLock();
        $lockList = $lock -> getLockForList();

        foreach($lockList -> items() as $l){
            $l['daytime'] = intval((time()-strtotime($l['create_time']))/86400)+1;
            if($l['daytime'] > $l['lock_time']){
                $l['daytime'] = $l['lock_time'];
            }
        }
        return $lockList -> items() ? view('lock_list',['List' => $lockList , 'Count' => $lockCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }


    /*
    * @ lockSearch    搜索锁仓计划
    * */
    public function lockSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $lock = new LockModel();
                $lockList = $lock->getSearchLockByKeyword($post_data);
                if (empty($lockList->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    foreach($lockList -> items() as $l){
                        $l['daytime'] = intval((time()-strtotime($l['create_time']))/86400)+1;
                        if($l['daytime'] > $l['lock_time']){
                            $l['daytime'] = $l['lock_time'];
                        }
                    }
                    return view('lock_list' , ['List' => $lockList , 'Count' => $lock->getCountSearchLockByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }

    /*
    * @ lockSort    按排序信息查看锁仓
    * */
    public function lockSort(){
        if(isset($_GET['key']) && !empty($_GET['key'])){
            $data = $this -> request -> get();
            $lock = new LockModel();
            $lockCount = $lock -> getCountLock();
            $lockList = $lock -> getLockForList($data['key']);
            foreach($lockList -> items() as $l){
                $l['daytime'] = intval((time()-strtotime($l['create_time']))/86400)+1;
                if($l['daytime'] > $l['lock_time']){
                    $l['daytime'] = $l['lock_time'];
                }
            }
            return $lockList -> items() ? view('lock_list',['List' => $lockList , 'Count' => $lockCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
        }else{
            ReturnJson::ReturnA("无效的操作...");
        }

    }


    /*
     * @ lockBreak   中断指定用户锁仓
     * */
    public function lockBreak(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $lock = new LockModel();
            $lockBreak = $lock -> breakLockById($id);
            //var_dump($lockBreak);die;
            //锁仓资产转入可用资产
            $lockInfo = $lock -> getOneLockInfoByKey($id);
            $user = new User();
            //增加可用资产
            $user -> updateAssetById($lockInfo['user_id'],$lockInfo['number'],'asset_avali');
            PublicFunction::SetCapitalLog($lockInfo['user_id'],$lockInfo['number'],9);
            //减少固定资产
            $user -> updateAssetById($lockInfo['user_id'],$lockInfo['number'],'asset_fixed',2);
            return $lockBreak ?  ReturnJson::ReturnJ("用户锁仓计划中断成功...") : ReturnJson::ReturnJ("用户锁仓计划中断失败，请重新操作...");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }
    }
















}