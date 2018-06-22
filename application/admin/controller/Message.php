<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 15:57
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\Message as MessageModel;
use app\admin\validate\MessageValidate;
use app\common\controller\ReturnJson;


class Message extends Controller
{
    //信息列表
    public function messageList(){
        $message = new MessageModel();
        $messageCount = $message -> getCountMessage();
        $messageList = $message -> getMessageList();
        return $messageList -> items() ? view('message_list',['List' => $messageList , 'Count' => $messageCount]) : ReturnJson::ReturnH('未查询到相关数据信息...','#/message/messageAdd');
    }

    //添加信息
    public function messageAdd(){
        if($this->request->isPost()){
            $data = $this -> request -> post();
            $validate = new MessageValidate();
            if($validate -> check($data)){
                $message = new MessageModel();
                if($message->checkTypeState($data['type'])){
                    return  ReturnJson::ReturnJ("所提交的类型代码已经存在，请重新确认...","false");
                }
                return $message -> addMessage($data) ? ReturnJson::ReturnJ("信息数据创建成功...","success","/message/messageList") : ReturnJson::ReturnJ("信息创建失败，请重新提交...","false");
            }else{
                return ReturnJson::ReturnJ($validate -> getError(),"false");
            }
        }else{
            return view('message_add');
        }
    }

    public function messageUpdate(){
        //修改提交信息
        if($this -> request -> isPost()){
            $data = $this -> request -> Post();
            if(isset($data['id']) && empty($data['id'])){
                return ReturnJson::ReturnJ('无效的数据操作...','false','/message/messageList');
            }
            $validate = new MessageValidate();
            if($validate -> check($data)){
                $message = new MessageModel();
                $check_type = $message->checkTypeState($data['type']);
                if($check_type && $check_type['id'] != $data['id']){
                    return  ReturnJson::ReturnJ("所提交的类型代码已经存在，请重新确认...","false");
                }
                 return $message -> updateMessage($data['id'],$data) ? ReturnJson::ReturnJ('数据更新成功...','success','/message/messageList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...','false');
            }
            return ReturnJson::ReturnJ($validate -> getError(),'false');
        }

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $message = new MessageModel();
            $getOne = $message -> getOneMessage($id);
            return $getOne ?  view('message_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的信息数据信息...","#/message/message_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }

    public function messageDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $message = new MessageModel();
            return $message -> delMessage($id)  ? ReturnJson::ReturnJ("已成功删除此信息...") : ReturnJson::ReturnJ($message -> getError(),"false");
        }
        return ReturnJson::ReturnJ("非法的数据提交信息!","false");
    }





}