<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/15
 * Time: 16:22
 */

namespace app\admin\model;
use think\Model;

class Extract extends Model
{
    public static $tableName = 'think_extract';

    protected $autoWriteTimestamp = true;

    //取值提币状态显示
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '审核中',1 => '已通过',2 => '已驳回'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d H:i:s',$value);
    }


    /*
     * @getExtractForList 提币列表显示
     * */
    public function getExtractForList($key='id',$des='DESC'){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.number,l.plat,l.address,l.payment,l.service_price,l.true_num,l.state,l.create_time')
            ->join('think_user r','l.user_id = r.id','left')
            -> order('l.'.$key.' '.$des.'')
            ->group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/extract/extractList' ]);
    }


    /*
     * @ getCountExtract   统计提币数量
     * */
    public function getCountExtract(){
        return $this -> count('id');
    }

    /*
     * @ getOneExtractInfoById  获取指定提币信息
     * $id      提币ID
     * */
    public function getOneExtractInfoById($id,$field='id'){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.user_id,l.number,l.plat,l.address,l.payment,l.service_price,l.true_num,l.state,l.talk,l.create_time')
            ->join('think_user r','l.user_id = r.id','left')
            -> where('l.'.$field,$id)
            ->group('l.id')
            -> find();
    }

    /*
     * @ updateExtractInfoById  更新指定提币信息
     * $id      提币ID
     * */
    public function updateExtractInfoById($id,$data){
        return $this -> allowField(true)
                    -> where('id',$id)
                    -> update($data);
    }

    /*
     * @ getSearchExtractByKeyword  通过关键词搜索提币信息
     * $keyword      关键词
     * */
    public function getSearchExtractByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.number,l.plat,l.address,l.payment,l.service_price,l.true_num,l.state,l.create_time')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('l.id','like',$keyword)
            -> whereOr('r.number','like','%'.$keyword.'%')
            -> whereOr('l.plat','like','%'.$keyword.'%')
            -> whereOr('r.phone','like','%'.$keyword.'%')
            -> order('l.id DESC')
            -> paginate(10,false,['path' => '/admin/main#/extract/extractList' ]);
    }

    /*
     * @ getCountSearchExtractByKeyword  统计通过关键词搜索提币信息
     * $keyword      关键词
     * */
    public function getCountSearchExtractByKeyword($keyword){
        return $this -> alias('l')
            -> field('l.id')
            -> join('think_user r','l.user_id = r.id','left')
            -> where('l.id','like',$keyword)
            -> whereOr('r.number','like','%'.$keyword.'%')
            -> whereOr('l.plat','like','%'.$keyword.'%')
            -> whereOr('r.phone','like','%'.$keyword.'%')
            ->count();
    }

























}