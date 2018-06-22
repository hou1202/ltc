<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/22
 * Time: 9:30
 */

namespace app\index\controller;
use app\common\controller\CommController;

class Buy extends CommController
{
    public function buyList(){
        return $this -> fetch('buy/buylist');

    }

    public function remit(){
        return $this -> fetch('buy/remit');

    }

    public function trade(){
        return $this -> fetch('buy/trade');

    }

    public function tradelist(){
        return $this -> fetch('buy/tradelist');

    }
}