<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/15
 * Time: 16:10
 */
namespace app\admin\model;

use think\Model;
use think\Db;

class News  extends Model
{
    protected static $tableName = 'think_news';

    protected $autoWriteTimestamp = true;

    //取值发送用户显示
    protected function getUserIdAttr($value){
        $user_id[0] = $value;
        if($value){
            $user=Db::name("user")->field('number')->where('id',$value)->find();
            $user_id[1] = $user['number'];
        }else{
            $user_id[1] = '所有用户';
        }
        return $user_id;

    }

    //取值创建时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }

    //取值修改时间显示
    protected function getUpdateTimeAttr($value){
        return date('Y-m-d',$value);
    }


    /*
    * @getNewsForList  通知列表显示用户
    * */
    public function getNewsForList(){
        return $this -> field('id,title,author,content,user_id,create_time')
            -> where('is_del',0)
            -> order('id DESC')
            -> paginate(10,false,['path' => '/admin/main#/news/newsList' ]);
    }

    //按id统计通知数量
    public function getCountNews(){
        return $this -> field('id') -> count();
    }

    /*
     * @ getOneNewsById  获取指定新闻信息
     * $id      新闻信息ID
     * */
    public function getOneNewsById($id){
        return $this ->field('id,title,author,content,user_id,create_time')
            ->where('id',$id)
            -> find();
    }


    /*
     * @ updateNewsInfoById  更新指定新闻信息
     * $id      提币ID
     * */
    public function updateNewsInfoById($id,$data){
        return $this -> allowField(true)
            -> where('id',$id)
            -> update($data);
    }


    //搜索新闻
    public function getSearchNews($keyword){
        return $this -> alias('n')
                     -> field('n.id,n.title,n.author,n.content,n.user_id,n.create_time')
                     -> join('think_user u','n.user_id = u.id','left')
                     -> whereOr('u.number','like','%'.$keyword.'%')
                     -> whereOr('u.phone','like','%'.$keyword.'%')
                     -> group('n.id')
                     -> order('n.id DESC')
                     -> paginate(5,false,['path' => '/admin/main#/news/newsList' ]);
                     //-> select();
    }

    //按id统计搜索新闻数量
    public function getCountSearchNews($keyword){
        return $this -> alias('n')
                    -> field('n.id')
                    -> join('think_user u','u.id = n.user_id','left')
                    -> where('u.number','like','%'.$keyword.'%')
                    -> whereOr('u.phone','like','%'.$keyword.'%')
                    -> group('n.id')
                    ->count();
    }


}