<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/7
 * Time: 11:00
 */

namespace app\index\common\controller;


class DoJob
{
    public function job( $param = array() )
     {
         $time = time();
         echo "Time: {$time}, Func: ".get_class()."::".__FUNCTION__."(".json_encode($param).")\n";
    }
}