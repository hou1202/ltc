<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/27
 * Time: 15:55
 */

namespace app\index\model;
use think\Model;

class News extends Model
{
    public static $tableName = 'think_news';

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }

    /*
     * @getNewsForIndex   取出最新的数据为INDEX
     * $id          用户ID
     * $limit       取出条数
     *
     * */
    public function getNewsForIndex($id,$limit=3){
        return $this -> field('id,title,create_time')
            -> where('is_del',0)
            ->where('user_id',0)
            ->whereOr('user_id',$id)
            ->order('id DESC')
            ->limit($limit)
            -> select();
    }


    /*
     * @getNewsForList   取出最新的数据为列表
     * $id          用户ID
     * $limit       取出条数
     *
     * */
    public function getNewsForList($id){
        return $this -> field('id,title,create_time')
            -> where('is_del',0)
            ->where('user_id',0)
            ->whereOr('user_id',$id)
            ->order('id DESC')
            -> select();
    }

    /*
     * @ getNewsDetailById    获取新闻详情
     * $id                  新闻ID
     * @return 新闻完整信息
     * */
    public function getNewsDetailById($id){
        return $this -> field('id,title,author,content,create_time')
            -> where('id',$id)
            -> where('is_del',0)
            -> find();

    }





}