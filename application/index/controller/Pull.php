<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/22
 * Time: 14:25
 */

namespace app\index\controller;
use app\common\controller\CommController;

class Pull extends CommController
{
    public function pull(){
        return $this -> fetch('pull/pull');

    }

    public function pullDetail(){
        return $this -> fetch('pull/pulldetail');

    }
}