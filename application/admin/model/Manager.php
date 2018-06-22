<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/30
 * Time: 17:41
 */

namespace app\admin\model;


use think\Model;

class Manager extends Model
{
    public function login($account,$password){
        $where = [
            'account' => $account,
            'password' => $password,
        ];
        return $this -> field('id,account,name,power')
                     -> where($where)
                     -> limit(1)
                     -> find();
    }

}