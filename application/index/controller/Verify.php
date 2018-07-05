<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 13:34
 */

namespace app\index\controller;

use app\common\controller\IndexController;
use app\common\model\VerifyModel;
use app\index\model\User;
use think\Config as ThinkConfig;

class Verify extends IndexController
{
    const TYPE_LOGIN = 0;       //注册
    const TYPE_EDIT_PASSWD = 1;     //忘记/修改登录密码
    const TYPE_TRADE_PASSWD = 2;     // 修改交易密码
    const TYPE_PULL = 3;     // 提币
    const TYPE_TRADE_BUY = 4;     // 交易购买
    const TYPE_ACCOUNT = 5;         //修改帐号资料



    public static $phoneVerify = [
        self::TYPE_LOGIN => 'login_',
        self::TYPE_EDIT_PASSWD => 'epass_',
        self::TYPE_TRADE_PASSWD => 'trade_',
        self::TYPE_PULL => 'pull_',
        self::TYPE_TRADE_BUY => 'buy_',
        self::TYPE_ACCOUNT => 'account_',
    ];

    /**
     * 获取验证码
     * @return array
     */
    public function get()
    {
        $data = $this->request->post();
        //var_dump($data);
        //检查手机号
        if (isset($data['phone']) && preg_match('/^1[23465789]{1}\d{9}$/', $data['phone']) == 0) {
            return $this->jsonFail('手机号格式不正确');
        }
        $verify_fix = isset($data['type']) ? self::$phoneVerify[(int)$data['type']] : null;
        if ($verify_fix == null) {
            return $this->jsonFail('验证码类型错误');
        }
        //其它的验证规则
        switch ($data['type']) {
            case self::TYPE_LOGIN:
                $user = new User();
                $userInfo = $user->getUserPartByKey($data['phone'],'phone');
                if (!empty($userInfo)) {
                    return $this->jsonFail('该手机号已经注册过了');
                };
                break;
            case self::TYPE_EDIT_PASSWD || self::TYPE_TRADE_PASSWD || self::TYPE_PULL || self::TYPE_TRADE_BUY || self::TYPE_ACCOUNT:
                $user = new User();
                $userInfo = $user->getUserPartByKey($data['phone'],'phone');
                if (!$userInfo) {
                    return $this->jsonFail('该用户不存在');
                }
                break;
            default:
                return $this->jsonFail('发送失败');
        }

        $verify = new VerifyModel();

        $data['ip'] = $this->request->ip();
        if($verify->send($data['type'], $data['phone'], $data)) {
            return $this->jsonSuccess('发送成功');
        } else {
            return $this->jsonFail('发送失败');
        }
    }

}
