<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 15:57
 */

namespace app\admin\controller;


use think\Controller;
use app\admin\model\News as NewsModel;
use think\Validate;


class News extends Controller
{
    //新闻列表
    public function newsList(){
        $news = new NewsModel();
        $newsCount = $news -> getCountNews();
        $newsList = $news -> order('id DESC') -> paginate(5,false,['path' => '/admin/main#/news/newsList' ]);
        return $newsList -> items() ? view('news_list',['List' => $newsList , 'Count' => $newsCount]) : ReturnJson::ReturnA('未查询到相关数据信息...');
    }

    //添加新闻
    public function newsAdd(){
        if($this->request->isPost()){
            $data = $this -> request -> post();
            $validate = new NewsValidate();
            if($validate -> check($data)){
                $news = new NewsModel();
                return $news -> save($data) ? ReturnJson::ReturnJ("新闻数据创建成功...","success","/news/newsList") : ReturnJson::ReturnJ("新闻创建失败，请重新提交...","false");
            }else{
                return ReturnJson::ReturnJ($validate -> getError(),"false");
            }
        }else{
            return view('news_add');
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
                 return $news -> save($data,['id' => $data['id']]) ? ReturnJson::ReturnJ('数据更新成功...','success','/news/newsList') : ReturnJson::ReturnJ('数据更新失败，请重新操作...','false');
            }
            return ReturnJson::ReturnJ($validate -> getError(),'false');
        }

        //获取、展示修改信息
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $news = new NewsModel();
            $getOne = $news -> where('id',$id) -> limit(1) -> find();
            return $getOne ?  view('news_update',['getOne' => $getOne]) : ReturnJson::ReturnH("未获取到相应的新闻数据信息...","#/news/news_list");
        }else{
            ReturnJson::ReturnA("无效的修改操作...");
        }

    }

    public function newsDel(){
        if(isset($_GET['id']) && !empty($_GET['id'])){
            $id = $this -> request -> get('id');
            $news = new NewsModel();
            return $news -> where('id',$id) -> delete() ? ReturnJson::ReturnJ("已成功删除此信息...") : ReturnJson::ReturnJ($news -> getError(),"false");
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
                $List = $news->getSerachNews($post_data);
                if (empty($List->items())) {
                    return ReturnJson::ReturnA('未查询到相关数据，请重新搜索！');
                } else {
                    return view('news_list' , ['List' => $List , 'Count' => $news->getCountSerachNews($post_data)]);
                }
            }
        }else{
            return ReturnJson::ReturnA('非法数据操作!');
        }

    }



}