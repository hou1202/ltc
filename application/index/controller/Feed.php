<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/4
 * Time: 17:29
 */

namespace app\index\controller;
use app\common\controller\CommController;
use app\index\model\Feed as FeedModel;
use think\Cookie;
use think\Hook;
use think\Db;


class Feed extends CommController
{

    //反馈
    public function feedLog(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        if($this -> request ->isPost()){
            $data = $this -> request -> post();
            if(!isset($data['content']) || empty($data['content'])){
                return $this ->jsonFail('提交反馈信息有误，请重新操作');
            }
            if(isset($data['img'])){
                $img = null;
                foreach($data['img'] as $value){
                    $img .= $value.'|';
                }
                $data['img']= substr($img,0,strlen($img)-1);
            }
            $data['user_id'] = $id;
            $feed = new FeedModel();
            //更改状态，并返回结果
            if($feed -> saveFeed($data)){
                return $this ->jsonSuccess('交反馈信息已提交成功，管理员将为您处理','/index/feed/feedList');
            }else{
                return $this ->jsonFail('交反馈信息提交失败，请重新操作');
            }
        }
        return $this -> fetch('feed/feed');
    }


    //反馈列表
    public function feedList(){
        Hook::listen('CheckAuth',$params);
        $id = Cookie::get('user');
        $feed = new FeedModel();
        $list = $feed -> getFeedByUser($id);
        return $this -> fetch('feed/feed_list',['List'=>$list]);
    }


    //反馈详情
    public function feedDetail(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $_GET['id'];
            $feed = new FeedModel();
            $getOne = $feed -> getFeedDetailById($id);
            if(!empty($getOne->img)){
                $getOne->img = explode('|',$getOne->img);
            }
            return $this -> fetch('feed/feed_detail',['getOne'=>$getOne]);
        }
    }

    //关于LTC
    public function aboutLtc(){
        Hook::listen('CheckAuth',$params);
        $about = Db::name('message') -> field('title,content') -> where('type',2) -> find();
        return $this -> fetch('feed/about',['About'=>$about]);
    }




    /*图像上传*/
    public function uploader()
    {
        // 获取表单上传文件
        $files = request()->file('');
        foreach ($files as $file) {
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
            if ($info) {
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $path['name'] = DS . 'uploads/' . $info->getSavename();
            } else {
                // 上传失败获取错误信息
                return $this->error($file->getError());
            }
        }
        echo json_encode($path);
    }
}