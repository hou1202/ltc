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

    //密码MD5自动加密
    protected function setPasswordAttr($value){
        return md5($value);
    }

    //取值时间显示
    protected function getCreateTimeAttr($value){
        return date('Y-m-d',$value);
    }

    protected function getGradeAttr($value){
        $grade = [0 => '青铜小兵' , 1 => '白银连长', 2 => '黄金将军', 3 => '铂金元帅', 4 => '王者大帝'];
        return $grade[$value];
    }

    /*
     * @ getUserInfoByMobile    通过手机号码获取用户完整信息
     * $phone   用户手机号码
     * @return 用户完整信息
     * */
    public function getUserInfoByMobile($phone){
        return $this->where(['phone' => $phone])->find();
    }

    /*
     * @ getUserIdByMobile    通过手机号码获取用户ID信息
     * $phone   用户手机号码
     * @return 用户完整信息
     * */
    public function getUserIdByMobile($phone){
        return $this->field('id')->where(['phone' => $phone])->find();
    }



    /*
     * @ getParentUserIdByInvite    通过邀请码，获取父级用户ID
     * $invite 父级用户邀请码
     * @return id
     * */
    public function getParentUserIdByInvite($invite){
        return $this -> field('id') -> where('invite',$invite) -> find();
    }

    /*
     * @ getLoginUserInfoByPhone 通过手机号码/密码，获取登录用户信息
     * $phone   登录用户手机号码
     * $password    登录用户密码
     * @return  id,phone,state
     * */
    public function getLoginUserInfoByPhone($phone,$password){
        return $this -> field('id,phone,state')
                     -> where('phone',$phone)
                     -> where('password',md5($password))
                     -> find();
    }

    /*
     * @ getInviteUserByPId 通过PId，查找该用户下的当有邀请用户
     * $pid     用户的ID，即父即用户的PID
     * @return  id,portrait,phone,user_name,invite,grade,create_time
     * */
    public function getInviteUserByPId($pid){
        return  $this ->alias('u')
            -> field('sum(r.money) as t_money,u.id,u.portrait,u.phone,u.user_name,u.invite,u.grade,u.create_time')
            ->join('think_capital_record r','u.id = r.sid','inner')
            -> where('u.p_id',$pid)
            ->group('r.sid')
            -> select();
    }

    /*
     *@setFieldById 更新某个字段的值
     * $id  更新数据ID
     * $field 更新的字段
     * $value 更新字段的值
     * */
    public function setFieldById($id,$field,$value){
        return $this -> where('id',$id) -> setField($field,$value);
    }



}