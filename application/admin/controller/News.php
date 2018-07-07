<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 15:57
 */

namespace app\admin\controller;


use app\common\controller\CommController;
use app\common\controller\ReturnJson;
use app\admin\model\News as NewsModel;
use app\admin\model\User;
use app\admin\Validate\NewsValidate;


class News extends CommController
{
    //新闻列表
    public function newsList(){
        $news = new NewsModel();
        $newsCount = $news -> getCountNews();
        $newsList = $news -> getNewsForList();
        return $newsList -> items() ? view('news_list',['List' => $newsList , 'Count' => $newsCount]) : ReturnJson::ReturnH('未查询到相关数据信息...','#/news/newsAdd');
    }

    //添加新闻
    public function newsAdd(){
        if($this->request->isPost()){
            $data = $this -> request -> post();
            //var_dump($data);die;
            $validate = new NewsValidate();
            if($validate -> check($data)){
                $news = new NewsModel();
                return $news -> save($data) ? ReturnJson::ReturnJ("新闻数据创建成功...","success","/news/newsList") : ReturnJson::ReturnJ("新闻创建失败，请重新提交...","false");
            }else{
                return ReturnJson::ReturnJ($validate -> getError(),"false");
            }
        }else{
            $user = new User();
            $number = $user -> getUserNumber();
            return view('news_add',['Number'=>$number]);
        }
    }

    public function newsUpdate(){
        //修改提交信息
        if($this -> request -> isPost()){
            $data = $this -> request -> Post();
            if(isset($data['id']) && empty($data['id'])){
                return ReturnJson::ReturnJ('无效的数据操作...','false','/news/newsList');
            }
            $validate = new NewsValidate();
            if($validate -> check($data)){
                $news = new NewsModel();
                 return $news -> updateNewsInfoById($data['id'],$data) ? ReturnJson::ReturnJ('数据更新成功...','success','/news/newsList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...','false');
            }
            return ReturnJson::ReturnJ($validate -> getError(),'false');
        }

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $news = new NewsModel();
            $getOne = $news -> getOneNewsById($id);
            return $getOne ?  view('news_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的新闻数据信息...","#/news/news_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }

    //新闻删除
    public function newsDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $news = new NewsModel();
            $data['is_del']=time();
            return $news -> updateNewsInfoById($id,$data) ? ReturnJson::ReturnJ("已成功删除此信息...") : ReturnJson::ReturnJ($news -> getError(),"false");
        }
        return ReturnJson::ReturnJ("非法的数据提交信息!","false");
    }

    //新闻搜索
    public function newsSerach()
    {

        if ($this->request->isGet()  && isset($_GET['keyword'])){
            $post_data = $this->request->get('keyword');
            if (empty($post_data)) {
                return ReturnJson::ReturnA('关键字不能为空，请重新搜索！');
            } else {
                $news = new NewsModel();
                $List = $news->getSearchNews($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('news_list' , ['List' => $List , 'Count' => $news->getCountSearchNews($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }


    /*
     * @newsUser            信息推送用户查找
     *
     * */
    public function newsUser(){
        if ($this->request->isPost()){
            $post_data = $this->request->post('key');
            if (empty($post_data)) {
                return $this ->jsonFail("关键字不能为空，请重新搜索！");
            } else {
                $user = new User();
                $List = $user->getNewsUserByKeyword($post_data);
                if (empty($List)) {
                    return $this ->jsonFail("未查询到相关数据，请重新搜索");
                } else {
                    $html = '<select class="form-control" name="user_id" id="select_user">';
                    $html .= '<option value="0">所有用户</option>';
                    foreach($List as $value){
                        $html .= '<option value="'.$value['id'].'">'.$value['number'].'</option>';
                    }
                    $html .= '</select>';
                    return $this ->jsonSuccess($html);
                }
            }
        }else{
            return $this ->jsonFail("非法数据操作");
        }
    }



}