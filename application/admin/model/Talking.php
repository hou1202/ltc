<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/15
 * Time: 14:10
 */

namespace app\admin\model;



use think\Model;

class Talking extends Model
{
    protected static $tableName = 'think_talking';

    protected $autoWriteTimestamp = true;




    //取值创建时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }

    //取值修改时间显示
    protected function getUpdateTimeAttr($value){
        return date('Y-m-d',$value);
    }

    /*
    * @getNoticeForList 反馈列表显示用户
    * */
    public function getTalkForList(){
        return $this -> alias('l')
            -> field('r.user_name as uid,l.id,l.content,l.reply,l.create_time')
            ->join('think_user r','l.uid = r.id','left')
            ->where('isdel',0)
            ->order('l.id DESC')
            ->group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/talking/talkList' ]);
    }


    /*
     * @ getTalkUser   统计反馈信息数量
     * */
    public function getTalkUser(){
        return $this -> count('id');
    }

    /*
     * @ getOneTalkById  获取指定反馈信息
     * $id      反馈信息ID
     * */
    public function getOneTalkById($id){
        return $this -> alias('n')
            ->field('u.user_name as uid,n.id,n.content,n.reply,n.create_time')
            ->join('think_user u','u.id = n.uid','left')
            ->where('n.id',$id)
            ->group('n.id')
            -> find();
    }

    /*
     * @ updateTalkById  更新指定反馈信息
     * $id      反馈信息ID
     * */
    public function updateTalkById($data,$id){
        return $this -> allowField(true) -> save($data, ['id' => $id]);
    }



    /*
     * @ getSearchTalkByKeyword  通过关键词搜索反馈信息
     * $keyword      关键词
     * */
    public function getSearchTalkByKeyword($keyword){
        return $this  -> alias('l')
            -> field('r.user_name as uid,l.id,l.content,l.reply,l.create_time')
            -> join('think_user r','l.uid = r.id','left')
            -> where('isdel',0)
            -> where('l.id','like','%'.$keyword.'%')
            -> whereOr('r.user_name','like','%'.$keyword.'%')
            -> order('l.id DESC')
            -> group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/talking/talkList' ]);
    }

    /*
     * @ getCountSearchTalkByKeyword  统计通过关键词搜索反馈信息
     * $keyword      关键词
     * */
    public function getCountSearchTalkByKeyword($keyword){
        return $this -> alias('l')
            -> field('l.id')
            -> join('think_user r','l.uid = r.id','left')
            -> where('isdel',0)
            -> where('l.id','like','%'.$keyword.'%')
            -> whereOr('r.user_name','like','%'.$keyword.'%')
            -> count();
    }

}