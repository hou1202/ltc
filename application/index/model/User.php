<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2017/12/27
 * Time: 14:14
 */
namespace app\index\model;

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

    //取值时间显示
    protected function getCreateTimeAttr($value){
        $create_time[0] = date('Y-m-d',$value);
        $create_time[1] = $value;
        return $create_time;
    }



    /*
     * @ getUserAllByKey    通过$key获取用户完整信息
     * $key                 键值
     * $field               键名：默认为ID
     * @return 用户完整信息
     * */
    public function getUserAllByKey($key,$field='id'){
        return $this->where($field,$key)->find();
    }

    /*
     * @ getUserPartByKey    通过$key获取用户部分信息
     * $key                 键值
     * $field               键名：默认为ID
     * @return 用户部分信息
     * */
    public function getUserPartByKey($key,$field='id'){
        return $this->field('id,number,pwd_login,pwd_trade,phone,asset_avali,asset_fixed,state')->where($field,$key)->find();
    }


    /*
     * @ loginUserCheck     登录用户验证
     * $phone   登录用户手机号码
     * $password    登录用户密码
     * @return  id,phone,state
     * */
    public function loginUserCheck($phone,$password){
        return $this -> field('id,number,phone,state')
                     -> where('phone',$phone)
                     -> where('pwd_login',md5($password))
                     -> find();
    }


    /*
     * @ insertUserReturnId     增加新用户
     * $data                    用户数据
     * @return                  新增用户ID
     * */
    public function insertUserReturnId($data){
        if($this -> allowField(true) -> save($data)){
            return $this ->getLastInsID();
        }else{
            return false;
        }
    }


    /*
     *@setFieldById 更新某个字段的值
     * $id  更新数据key值
     * $field  更新数据key名
     * $data 更新数据
     * */
    public function setFieldById($data,$id,$field='id'){
        return $this -> allowField(true) ->  save($data,[$field => $id]);
    }

    /*
     * @setUserAsset        增加/减少指定用户的用户、固定资产
     * $id                  条件KEY
     * $key_field           KEY字段
     * $type                增加/减少类型 (固定资产不可减少，只能增加)
     *      1=》 增加
     *      2=》 减少
     * $field               改变值字段可用户资产/固定资产
     *
     * */
    public function setUserAsset($id,$num,$type=1,$field='asset_avali',$key_field='id'){
        switch($type){
            case 1:
                return $this -> where($key_field,$id)->setInc($field,$num);
                break;
            case 2:
                return $this -> where($key_field,$id)->setDec('asset_avali',$num);
                break;
            default:
                return false;
                break;
        }
    }



}