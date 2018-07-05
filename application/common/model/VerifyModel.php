<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 12:14
 */

namespace app\common\model;


use think\Config as ThinkConfig;
use think\Db;
use think\Log;
use think\Session;

class VerifyModel
{

    const TYPE_LOGIN = 0;       //注册
    const TYPE_EDIT_PASSWD = 1;     //忘记密码
    const TYPE_TRADE_PASSWD = 2;     // 修改交易密码
    const TYPE_PULL = 3;     // 提币
    const TYPE_TRADE_BUY = 4;     // 交易购买
    const TYPE_ACCOUNT = 5;         //修改帐号资料

    public static $sVerifyLogTable = 'think_log_verify';

    public static $sVerifyPrefix = [
        self::TYPE_LOGIN => 'login_',
        self::TYPE_EDIT_PASSWD => 'epass_',
        self::TYPE_TRADE_PASSWD => 'trade_',
        self::TYPE_PULL => 'pull_',
        self::TYPE_TRADE_BUY => 'buy_',
        self::TYPE_ACCOUNT => 'account_',
    ];

    /**
     * 发送验证码
     * @param $type int 验证码类型
     * @param $mobile string 手机号
     * @param $logData array 日志记录
     * @return bool
     */
    public function send($type, $mobile, $logData){
        $verifyPrifix = static::$sVerifyPrefix[$type];
        //发送验证码
        $verifyCode = rand(100000, 999999);
        if(Session::has($verifyPrifix . $mobile)){
            $code = Session::get($verifyPrifix . $mobile);
            $this::flushVerify($code, $type, $mobile);
        }

        if ($this->sendVerify($mobile, $verifyCode,$type)) {
            //记录验证码
            $logData['verify'] = $verifyCode;
            Db::table(static::$sVerifyLogTable)->insert($logData);
            //$cache->set($verifyPrifix . $mobile, $verifyCode, 10 * 60);
            //$cache->set($verifyPrifix . 's_' . $mobile, $verifyCode, 60);     //用于计时60秒之内不可重复获取验证码
            Session::set($verifyPrifix . $mobile, $verifyCode);
            return true;
        }
        return false;
    }

    /**
     * 检查验证码是否正确
     * @param $code string 验证码
     * @param $type int    验证码类型
     * @param $mobile string 手机号
     * @return bool
     */
    public static function check($code, $type, $mobile){
        if(Session::has(self::$sVerifyPrefix[$type] . $mobile)){
            return $code == Session::get(self::$sVerifyPrefix[$type] . $mobile);
        }else{
            return false;
        }
    }

    /**
     * 刷新验证码
     * @param $code string 验证码
     * @param $type int    验证码类型
     * @param $mobile string 手机号
     * @throws \think\Exception
     */
    public static function flushVerify($code, $type, $mobile){
        //删除该验证码
        $verifyPrifix = static::$sVerifyPrefix[$type];
        $key = $verifyPrifix . $mobile;
        Session::delete($key);
        //更新数据库log
        Db::table(static::$sVerifyLogTable)->where('phone='.$mobile.' AND type='.$type.' AND verify='.$code)->update(['status'=>1, 'e_time'=>date('Y-m-d H:i:s')]);
    }

    /**
     * 向验证码服务器发送请求
     * @param $mobile string 手机号
     * @param $verifyCode string 验证码
     * @return bool
     */
    private function sendVerify($mobile, $verifyCode,$type)
    {
        //var_dump($type);die;
        switch($type){
            case self::TYPE_LOGIN:
                $text = '【LTC】您的注册验证码为：' . $verifyCode . '，请在10分钟内完成验证';
                break;
            case self::TYPE_EDIT_PASSWD:
                $text = '【LTC】您的修改登录密码验证码为：' . $verifyCode . '，请在10分钟内完成验证';
                break;
            case self::TYPE_TRADE_PASSWD:
                $text = '【LTC】您的修改交易密码验证码为：' . $verifyCode . '，请在10分钟内完成验证';
                break;
            case self::TYPE_PULL:
                $text = '【LTC】您的提币申请验证码为：' . $verifyCode . '，请在10分钟内完成验证';
                break;
            case self::TYPE_TRADE_BUY:
                $text = '【LTC】您的购买交易验证码为：' . $verifyCode . '，请在10分钟内完成验证';
                break;
            case self::TYPE_ACCOUNT:
                $text = '【LTC】您的帐户资料修改验证码为：' . $verifyCode . '，请在10分钟内完成验证';
                break;

            default:
                $text = '【LTC】您的验证码为：' . $verifyCode . '，请在10分钟内完成验证';
        }
        //$text = '【折金券】您的验证码为：' . $verifyCode . '，请在10分钟内完成验证';
        $objectUrl = 'https://dx.ipyy.net/smsJson.aspx?action=send&userid=&account='
            . ThinkConfig::get('sms_account')
            . '&password='
            . ThinkConfig::get('sms_password')
            . '&mobile='
            . $mobile
            . '&content=' . urlencode($text) . '&sendTime=&extno=';
        try {
            $results = file_get_contents($objectUrl);
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return false;
        }
        return json_decode($results) -> returnstatus == 'Success' ? true : false;
    }




}