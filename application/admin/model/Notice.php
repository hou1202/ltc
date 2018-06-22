<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/15
 * Time: 14:10
 */

namespace app\admin\model;



use think\Model;

class Notice extends Model
{
    protected static $tableName = 'think_notice';

    protected $autoWriteTimestamp = true;


    //取值状态显示
    protected function getStateAttr($value){
        $state = [0 => '未读',1 => '已读'];
        return $state[$value];
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
    * @getNoticeForList 通知列表显示用户
    * */
    public function getNoticeForList(){
        return $this -> alias('l')
            -> field('r.user_name as uid,l.id,l.title,l.content,l.state,l.create_time')
            ->join('think_user r','l.uid = r.id','left')
            -> order('l.id DESC')
            ->group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/notice/noticeList' ]);
    }


    /*
     * @ getNoticeUser   统计通知信息数量
     * */
    public function getNoticeUser(){
        return $this -> count('id');
    }

    /*
     * @ getOneNoticeById  获取指定通知信息
     * $id      通知信息ID
     * */
    public function getOneNoticeById($id){
        return $this -> alias('n')
            ->field('u.user_name as uid,n.id,n.title,n.content,n.state,n.create_time')
            ->join('think_user u','u.id = n.uid','left')
            ->where('n.id',$id)
            ->group('n.id')
            -> find();
    }

    /*
     * @ updateNoticeById  更新指定通知信息
     * $id      通知信息ID
     * */
    public function updateNoticeById($data,$id){
        return $this -> allowField(true) -> save($data, ['id' => $id]);
    }

    /*
     * @ getNoticeUser   删除通知信息
     * $id      通知信息ID
     * */
    public function delNoticeById($id){
        return $this -> where('id',$id) -> delete();
    }

    /*
     * @ getSearchNoticeByKeyword  通过关键词搜索通知信息
     * $keyword      关键词
     * */
    public function getSearchNoticeByKeyword($keyword){
        return $this  -> alias('l')
            -> field('r.user_name as uid,l.id,l.title,l.content,l.state,l.create_time')
            ->join('think_user r','l.uid = r.id','left')
            -> where('l.id','like','%'.$keyword.'%')
            -> whereOr('l.title','like','%'.$keyword.'%')
            -> whereOr('l.uid','like','%'.$keyword.'%')
            -> order('l.id DESC')
            ->group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/notice/noticeList' ]);
    }

    /*
     * @ getCountSearchNoticeByKeyword  统计通过关键词搜索通知信息
     * $keyword      关键词
     * */
    public function getCountSearchNoticeByKeyword($keyword){
        return $this -> field('id')
            -> where('id','like','%'.$keyword.'%')
            -> whereOr('title','like','%'.$keyword.'%')
            -> whereOr('uid','like','%'.$keyword.'%')
            ->count();
    }

}