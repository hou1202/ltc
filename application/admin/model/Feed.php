<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/23
 * Time: 11:04
 */

namespace app\admin\model;
use think\Model;

class Feed extends Model
{

    public static $tableName = 'think_feed';

    protected $autoWriteTimestamp = true;

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }

    /*
     * 状态显示
     * 0 =》 文字状态
     * 1 =》 数值状态
     * */
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '未处理',1 => '已处理'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    //反馈图像处理
    protected function getImgAttr($value){
        return explode('|',$value);
    }

    /*
     * @getFeedForList 反馈列表显示
     * */
    public function getFeedForList($key='id',$des='DESC'){
        return $this -> alias('l')
            -> field('r.number as name,l.id,l.user_id,l.content,l.img,l.state,l.create_time')
            -> join('think_user r','l.user_id = r.id','left')
            -> order('l.'.$key.' '.$des.'')
            -> group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/feed/feedList' ]);
    }


    /*
     * @ getCountFeed   统计反馈数量
     * */
    public function getCountFeed(){
        return $this -> count('id');
    }

    /*
     * @ getOneFeedInfoById  获取指定反馈信息
     * $id      用户ID
     * */
    public function getOneFeedInfoById($id,$field='id'){
        return $this -> alias('l')
            -> field('r.number as name,l.id,l.user_id,l.content,l.img,l.reply,l.state,l.create_time')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('l.'.$field , $id)
            -> group('l.id')
            -> find();
    }

    /*
     * @ updateFeedInfoById  更新指定反馈信息
     * $id      反馈ID
     * */
    public function updateFeedInfoById($id,$data){
        return $this -> allowField(true)
            -> where('id',$id)
            -> update($data);
    }



    /*
     * @ getSearchFeedByKeyword  通过关键词搜索反馈信息
     * $keyword      关键词
     * */
    public function getSearchFeedByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as name,l.id,l.user_id,l.content,l.img,l.state,l.create_time')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('r.number','like','%'.$keyword.'%')
            -> whereOr('r.phone','like','%'.$keyword.'%')
            -> order('l.id DESC')
            -> paginate(10,false,['path' => '/admin/main#/feed/feedList' ]);
    }

    /*
     * @ getCountSearchFeedByKeyword  统计通过关键词搜索反馈信息
     * $keyword      关键词
     * */
    public function getCountSearchFeedByKeyword($keyword){
        return $this -> alias('l')
            -> field('l.id')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('r.number','like','%'.$keyword.'%')
            -> whereOr('r.phone','like','%'.$keyword.'%')
            ->count();
    }



}