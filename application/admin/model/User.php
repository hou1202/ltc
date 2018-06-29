<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/1/13
 * Time: 16:07
 */
namespace app\admin\model;


use think\Model;

class User extends Model
{
    public static $tableName = 'think_user';

    protected $autoWriteTimestamp = true;

    //登录密码MD5自动加密
    protected function setPwdLoginAttr($value){
        return md5($value);
    }

    //交易密码MD5自动加密
    protected function setPwdTradeAttr($value){
        return md5($value);
    }

    //取值帐号状态显示
    protected function getStateAttr($value){
        $state=array();
        $str = [0 => '禁用',1 => '正常'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    //取值签到状态显示
    protected function getSignStateAttr($value){
        $state=array();
        $str = [0 => '签到结束',1 => '正常签到'];
        $state[0] = $str[$value];
        $state[1] = $value;
        return $state;
    }

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }


    /*
     * @getUserForList 用户列表显示用户
     * */
    public function getUserForList($key='id',$des='DESC'){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.number,l.phone,l.portrait,l.share_id,l.asset_avali,l.asset_fixed,l.name,l.state,l.create_time')
            ->join('think_user r','l.p_id = r.share_id','left')
            -> order('l.'.$key.' '.$des.'')
            ->group('l.id')
            -> paginate(10,false,['path' => '/admin/main#/user/userList' ]);
    }


    /*
     * @ getCountUser   统计注册用户数量
     * */
    public function getCountUser(){
        return $this -> count('id');
    }

    /*
     * @ getOneUserInfoById  获取指定用户信息
     * $id      用户ID
     * */
    public function getOneUserInfoById($id,$field='id'){
        return $this -> where($field,$id) -> find();
    }

    /*
     * @ getSearchUserByKeyword  通过关键词搜索用户
     * $keyword      关键词
     * */
    public function getSearchUserByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.number,l.phone,l.portrait,l.share_id,l.asset_avali,l.asset_fixed,l.name,l.state,l.create_time')
            -> join('think_user r','l.p_id = r.share_id','left')
            -> where('l.id','=',$keyword)
            -> whereOr('l.phone','like','%'.$keyword.'%')
            -> whereOr('l.number','like','%'.$keyword.'%')
            -> whereOr('l.share_id','like','%'.$keyword.'%')
            -> whereOr('l.name','like','%'.$keyword.'%')
            -> order('l.id DESC')
            -> paginate(10,false,['path' => '/admin/main#/user/userList' ]);
    }

    /*
     * @ getCountSearchUserByKeyword  统计通过关键词搜索用户
     * $keyword      关键词
     * */
    public function getCountSearchUserByKeyword($keyword){
        return $this -> alias('l')
            -> field('r.number as p_number,l.id,l.number,l.phone,l.portrait,l.share_id,l.asset_avali,l.asset_fixed,l.name,l.state,l.create_time')
            -> join('think_user r','l.p_id = r.share_id','left')
            -> where('l.id','=',$keyword)
            -> whereOr('l.phone','like','%'.$keyword.'%')
            -> whereOr('l.number','like','%'.$keyword.'%')
            -> whereOr('l.share_id','like','%'.$keyword.'%')
            -> whereOr('l.name','like','%'.$keyword.'%')
            ->count();
    }

    /*
     * @ insertUserReturnId     增加新用户
     * $data                    用户数据
     * @return                  新增用户ID
     * */
    public function insertUserReturnId($data){
        return $this -> insertGetId($data);
    }

    /*
     * @updateAssetById         更新用户资产(可用/固定)
     * $id                      用户ID
     * $capital                 更新资产数据
     * $asset                   更新资产类型字段
     *          @asset_avali    可用资产（默认）
     *          @asset_fixed    固定资产
     * $type                    更新类型
     *          1   =》  增加资产（默认）
     *          2   =》  减少资产
     *          其他返回false
     * @return
     * */
    public function updateAssetById($id,$capital,$asset='asset_avali',$type=1){
        if($type == 1){
            return $this -> where('id',$id) -> setInc($asset, $capital);
        }elseif($type == 2){
            return $this -> where('id',$id) -> setDec($asset, $capital);
        }else{
            return false;
        }
    }


    /*
     * @getUserNumber           返回所有用户的会员编号
     * */
    public function getUserNumber(){
        return $this -> field('id,number')->where('state',1)->select();
    }


    /*
     * @ getNewsUserByKeyword  通过关键词搜索用户
     * $keyword      关键词
     * */
    public function getNewsUserByKeyword($keyword){
        return $this -> field('id,number')
                    -> whereOr('phone','like','%'.$keyword.'%')
                    -> whereOr('number','like','%'.$keyword.'%')
                    -> select();
    }


}