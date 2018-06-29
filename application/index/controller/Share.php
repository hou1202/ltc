<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/22
 * Time: 14:25
 */

namespace app\index\controller;
use app\common\controller\CommController;

class Share extends CommController
{
    public function share(){
        return $this -> fetch('share/share');
    }

    public function shareFriend(){
        return $this -> fetch('share/sharefriend');

    }

    public function shareProfit(){
        return $this -> fetch('share/shareprofit');

    }
}