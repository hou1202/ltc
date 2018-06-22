<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/15
 * Time: 18:55
 */

namespace app\admin\controller;

use app\common\controller\CommController;
use app\common\controller\PublicFunction;
use app\common\controller\ReturnJson;
use app\admin\model\Recharge as RechargeModel;
use app\admin\model\User;

class Recharge extends CommController
{
    /*
         * @ rechargeList   充币列表
         * */
    public function rechargeList(){
        $recharge = new RechargeModel();
        $rechargeCount = $recharge -> getCountRecharge();
        $rechargeList = $recharge -> getRechargeForList();

        return $rechargeList -> items() ? view('recharge_list',['List' => $rechargeList , 'Count' => $rechargeCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    /*
     * @ rechargeUpdate    操作充币信息
     * */
    public function rechargeUpdate(){
        //修改提交信息
        if($this -> request -> isPost()){
            $data = $this -> request -> Post();
            if(isset($data['id']) && empty($data['id'])){
                return ReturnJson::ReturnJ('无效的数据操作...','false','/recharge/rechargeList');
            }
            $recharge = new RechargeModel();
            if(isset($data['state']) && $data['state'] == 1){
                //驳回审核
                $user = new User();
                $oneRecharge = $getOne = $recharge -> getOneRechargeInfoById($data['id']);
                //充币至用户可用资产，并生成资产记录
                $user -> updateAssetById($oneRecharge['user_id'],$oneRecharge['number']);
                PublicFunction::SetCapitalLog($oneRecharge['user_id'],$oneRecharge['number'],12);
            }

            $updateRecharge = $recharge -> updateRechargeInfoById($data['id'],$data);
            if($updateRecharge){
                return ReturnJson::ReturnJ('数据更新成功...','success','/recharge/rechargeList');
            }else{
                return ReturnJson::ReturnJ('数据更新失败，请重新操作...','false');
            }

        }

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $recharge = new RechargeModel();
            $getOne = $recharge -> getOneRechargeInfoById($id);
            return $getOne ?  view('recharge_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的用户信息...","#/recharge/recharge_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }


    /*
   * @ rechargeSearch    搜索充币信息
   * */
    public function rechargeSearch()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if ($post_data=='') {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $recharge = new RechargeModel();
                $List = $recharge->getSearchRechargeByKeyword($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('recharge_list' , ['List' => $List , 'Count' => $recharge->getCountSearchRechargeByKeyword($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }

    /*
    * @ rechargeSort    按排序信息查看充币信息
    * */
    public function rechargeSort(){
        //获取、展示修改信息
        if(isset($_GET['key']) && !empty($_GET['key'])){
            $data = $this -> request -> get();
            $recharge = new RechargeModel();
            $rechargeCount = $recharge -> getCountRecharge();
            $rechargeList = $recharge -> getRechargeForList($data['key']);
            return $rechargeList -> items() ? view('recharge_list',['List' => $rechargeList , 'Count' => $rechargeCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
        }else{
            ReturnJson::ReturnA("无效的操作...");
        }

    }

}