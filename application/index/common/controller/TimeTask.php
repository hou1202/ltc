<?php
/**
 * Created by PhpStorm.
 * User: Hou-ShiShu
 * Date: 2018/7/7
 * Time: 10:50
 */

namespace app\index\common\controller;


class TimeTask
{
    //保存所有定时任务
     public static $task = array();

     //定时间隔
     public static $time = 1;

     /**
     *开启服务
     *@param $time int
     */
     public static function run($time = null)
     {
         if($time)
         {
             self::$time = $time;
         }

         self::installHandler();

         /*
          * @pcntl_alarm        创建一个计时器
          * 参数等待的秒数
          * */
         pcntl_alarm(1);
     }

     /**
     *注册信号处理函数
     */
     public static function installHandler()
     {
         /*
          * @pcntl_signal   安装一个信号处理器
          * 信号编号     SIGALRM   14
          * */
         pcntl_signal(SIGALRM, array('TimeTask','signalHandler'));
     }

     /**
     *信号处理函数
     */
     public static function signalHandler()
     {
         self::task();
        //一次信号事件执行完成后,再触发下一次
        pcntl_alarm(self::$time);
     }

     /**
     *执行回调
     */
     public static function task()
     {
         if(empty(self::$task))
         {
             //没有任务,返回
             return ;
         }

         foreach(self::$task as $time => $arr)
        {
             $current = time();

             foreach($arr as $k => $job)
             {//遍历每一个任务
                 $func = $job['func'];    /*回调函数*/
                 $argv = $job['argv'];    /*回调函数参数*/
                 $interval = $job['interval'];    /*时间间隔*/
                 $persist = $job['persist'];    /*持久化*/

                 if($current == $time)
                 {
                     //当前时间有执行任务

                     //调用回调函数,并传递参数
                    call_user_func_array($func, $argv);

                     //删除该任务
                     unset(self::$task[$time][$k]);
                 }

                 if($persist)
                 {//如果做持久化,则写入数组,等待下次唤醒
                     self::$task[$current+$interval][] = $job;
                 }
             }

             if(empty(self::$task[$time]))
             {
                 unset(self::$task[$time]);
             }
        }
     }

     /**
     *添加任务
     */
     public static function add($interval, $func, $argv = array(), $persist = false)
     {
         if(is_null($interval))
         {
             return;
         }
         $time = time()+$interval;
     //写入定时任务
     self::$task[$time][] = array('func'=>$func, 'argv'=>$argv, 'interval'=>$interval, 'persist'=>$persist);
     }

     /**
     *删除所有定时器任务
     */
     public static function dellAll()
     {
         self::$task = array();
     }

}