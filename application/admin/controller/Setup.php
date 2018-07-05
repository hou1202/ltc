<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/23
 * Time: 15:45
 */

namespace app\admin\controller;
use app\common\controller\CommController;
use app\common\controller\ReturnJson;
use app\admin\model\Plan;
use app\admin\model\Price;
use app\admin\model\Message;
use app\admin\validate\PlanValidate;
use app\admin\validate\RechargeAddressValidate;
use app\admin\validate\ExtractPlatValidate;
use think\Db;
use think\Validate;

class Setup extends CommController
{

    /*
     * @ setPlanList   锁仓计划列表
     * */
    public function setPlanList(){
        $plan = new Plan();
        $feedCount = $plan -> getCountPlan();
        $feedList = $plan -> getPlanForList();
        return $feedList -> items() ? view('plan/plan_list',['List' => $feedList , 'Count' => $feedCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');

    }


    /*
     * @ setPlanAdd    新增锁仓计划
     * */
    public function setPlanAdd(){
        if($this->request->isPost()){
            $data = $this -> request -> post();
            $validate = new PlanValidate();
            if($validate -> check($data)){
                $plan = new Plan();
                $data['start_time'] = strtotime($data['start_time']);
                $data['end_time'] = strtotime($data['end_time']);
                return $plan -> insertPlan($data) ? ReturnJson::ReturnJ("新闻数据创建成功...","success","/setup/setPlanList") : ReturnJson::ReturnJ("新闻创建失败，请重新提交...","false");
            }else{
                return ReturnJson::ReturnJ($validate -> getError(),"false");
            }
        }else{
            return view('plan/plan_add');
        }
    }


    /*
     * @ setPlanUpdate    修改锁仓计划
     * */
    public function setPlanUpdate()
    {
        //修改提交信息
        if ($this->request->isPost()) {
            $data = $this->request->Post();
            if (isset($data['id']) && empty($data['id'])) {
                return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/setup/setPlanList');
            }
            $validate = new PlanValidate();
            if ($validate->check($data)) {
                $plan = new Plan();
                $data['start_time'] = strtotime($data['start_time']);
                $data['end_time'] = strtotime($data['end_time']);
                return $plan -> updatePlanById($data['id'],$data) ? ReturnJson::ReturnJ('数据更新成功...', 'success', '/setup/setPlanList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');
            }
            return ReturnJson::ReturnJ($validate->getError(), 'false');
        }

        //获取、展示修改信息
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $this->request->get('id');
            $plan = new Plan();
            $getOne = $plan->getOnePlanInfo($id);
            return $getOne ? view('plan/plan_update', ['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的数据信息...", "#/setup/setPlanList");
        } else {
            ReturnJson::ReturnA("无效的修改操作...");
        }
    }


    /*
   * @ setPlanDel    删除锁仓计划
   * */
    public function setPlanDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $plan = new Plan();
            return $plan -> delPlanById($id) ? ReturnJson::ReturnJ("已成功删除此数据信息...") : ReturnJson::ReturnJ($plan -> getError(),"false");
        }
        return ReturnJson::ReturnJ("非法的数据提交信息!","false");
    }


    /*
     * @ setLtcPriceList   LTC价格信息
     * */
    public function setLtcPriceList(){
        $price = new Price();
        $nowPrice = $price-> getNowPrice();
        $listPrice =  $price -> getPriceForList();
        $getCount = $price -> getCountPrice();
        return $nowPrice ? view('price/price_list',['getOne' => $nowPrice,'List'=>$listPrice,'Count'=>$getCount]) : ReturnJson::ReturnH('未查询到相关数据信息...','#/main/desktop');

    }


    /*
    * @ setLtcPriceUpdate    更新LTC价格信息
    * */
    public function setLtcPriceUpdate()
    {
        if ($this->request->isPost()) {
            $data = $this->request->Post();
            if (isset($data['id']) && empty($data['id'])) {
                return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/setup/setLtcPriceList');
            }
            $rule = [
                'price' => 'require|number|gt:0',
            ];

            $msg = [
                'price.require' => 'LTC价格不得为空...',
                'price.number' => 'LTC价格必须为大于0的正数...',
                'price.gt' => 'LTC价格必须为大于0的正整数...',
            ];
            $validate = new Validate($rule,$msg);
            if ($validate->check($data)) {
                $price = new Price();
                return $price-> insertNewPrice($data) ? ReturnJson::ReturnJ('数据更新成功...', 'success') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');
            }
            return ReturnJson::ReturnJ($validate->getError(), 'false');
        }
        return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/setup/setLtcPriceList');
    }


    /*
     * @ setRechargeList   充币地址列表
     * */
    public function setRechargeList(){
        $message = new Message();
        $Count = $message -> getCountMessage(4);
        $List = $message -> getMessageForList(4,'setRechargeList');
        return $List -> items() ? view('recharge/address_list',['List' => $List , 'Count' => $Count]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    /*
     * @ setRechargeAdd    新增充币地址
     * */
    public function setRechargeAdd(){
        if($this->request->isPost()){
            $data = $this -> request -> post();
            $validate = new RechargeAddressValidate();
            if($validate -> check($data)){
                $message = new Message();
                $data['type'] = 4;
                return $message -> saveMessageById($data) ? ReturnJson::ReturnJ("新闻数据创建成功...","success","/setup/setRechargeList") : ReturnJson::ReturnJ("新闻创建失败，请重新提交...","false");
            }else{
                return ReturnJson::ReturnJ($validate -> getError(),"false");
            }
        }else{
            return view('recharge/address_add');
        }
    }


    /*
     * @ setRechargeUpdate    修改充币地址
     * */
    public function setRechargeUpdate()
    {
        //修改提交信息
        if ($this->request->isPost()) {
            $data = $this->request->Post();
            if (isset($data['id']) && empty($data['id'])) {
                return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/setup/setRechargeList');
            }
            $validate = new RechargeAddressValidate();
            if ($validate->check($data)) {
                $message = new Message();
                return $message -> saveMessageById($data,$data['id']) ? ReturnJson::ReturnJ('数据更新成功...', 'success', '/setup/setRechargeList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');
            }
            return ReturnJson::ReturnJ($validate->getError(), 'false');
        }

        //获取、展示修改信息
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $this->request->get('id');
            $message = new Message();
            $getOne = $message -> getOneMessageById($id);
            return $getOne ? view('recharge/address_update', ['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的数据信息...", "#/setup/setRechargeList");
        } else {
            ReturnJson::ReturnA("无效的修改操作...");
        }
    }


    /*
   * @ setMessageDel    删除信息
   * */
    public function setMessageDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $message = new Message();
            return $message -> delMessageById($id) ? ReturnJson::ReturnJ("已成功删除此数据信息...") : ReturnJson::ReturnJ($message -> getError(),"false");
        }
        return ReturnJson::ReturnJ("非法的数据提交信息!","false");
    }

    /*
     * @ setExtractList   提币平台列表
     * */
    public function setExtractList(){
        $message = new Message();
        $Count = $message -> getCountMessage(3);
        $List = $message -> getMessageForList(3,'setExtractList');
        return $List -> items() ? view('extract/plat_list',['List' => $List , 'Count' => $Count]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }


    /*
     * @ setExtractAdd    新增提币平台
     * */
    public function setExtractAdd(){
        if($this->request->isPost()){
            $data = $this -> request -> post();
            $validate = new ExtractPlatValidate();
            if($validate -> check($data)){
                $message = new Message();
                $data['type'] = 3;
                return $message -> saveMessageById($data) ? ReturnJson::ReturnJ("新闻数据创建成功...","success","/setup/setExtractList") : ReturnJson::ReturnJ("新闻创建失败，请重新提交...","false");
            }else{
                return ReturnJson::ReturnJ($validate -> getError(),"false");
            }
        }else{
            return view('extract/plat_add');
        }
    }


    /*
     * @ setExtractUpdate    修改提币平台
     * */
    public function setExtractUpdate()
    {
        //修改提交信息
        if ($this->request->isPost()) {
            $data = $this->request->Post();
            if (isset($data['id']) && empty($data['id'])) {
                return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/setup/setExtractList');
            }
            $validate = new ExtractPlatValidate();
            if ($validate->check($data)) {
                $message = new Message();
                return $message -> saveMessageById($data,$data['id']) ? ReturnJson::ReturnJ('数据更新成功...', 'success', '/setup/setExtractList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');
            }
            return ReturnJson::ReturnJ($validate->getError(), 'false');
        }

        //获取、展示修改信息
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $this->request->get('id');
            $message = new Message();
            $getOne = $message -> getOneMessageById($id);
            return $getOne ? view('extract/plat_update', ['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的数据信息...", "#/setup/setExtractList");
        } else {
            ReturnJson::ReturnA("无效的修改操作...");
        }
    }


    /*
     * @ setMessageList   信息列表
     * */
    public function setMessageList(){
        $message = new Message();
        $type='1,2,5,6';
        $Count = $message -> getCountMessage($type);
        $List = $message -> getMessageForList($type,'setMessageList');
        return $List -> items() ? view('message/message_list',['List' => $List , 'Count' => $Count]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    /*
     * @ setMessageUpdate    修改信息
     * */
    public function setMessageUpdate()
    {
        //修改提交信息
        if ($this->request->isPost()) {
            $data = $this->request->Post();
            //var_dump($data);die;
            if (isset($data['id']) && empty($data['id'])) {
                return ReturnJson::ReturnJ('无效的数据操作...', 'false', '/setup/setMessageList');
            }
            $rule = [
                'title' => 'require|max:90',
                'content' => 'require'
            ];

            $msg = [
                'title.require' => '信息标题不得为空...',
                'title.max' => '信息标题最大不得超过30位...',
                'content.require' => '信息内容不得为空...',
            ];
            $validate = new Validate($rule,$msg);
            if ($validate->check($data)) {
                $message = new Message();
                return $message -> saveMessageById($data,$data['id']) ? ReturnJson::ReturnJ('数据更新成功...', 'success', '/setup/setMessageList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...', 'false');
            }
            return ReturnJson::ReturnJ($validate->getError(), 'false');
        }

        //获取、展示修改信息
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $id = $this->request->get('id');
            $message = new Message();
            $getOne = $message -> getOneMessageById($id);
            return $getOne ? view('message/message_update', ['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的数据信息...", "#/setup/setMessageList");
        } else {
            ReturnJson::ReturnA("无效的修改操作...");
        }
    }



}