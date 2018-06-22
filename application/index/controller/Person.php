<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/6/22
 * Time: 14:25
 */

namespace app\index\controller;
use app\common\controller\CommController;

class Person extends CommController
{
    public function person(){
        return $this -> fetch('person/person');

    }

    public function personData(){
        return $this -> fetch('person/data');

    }
}