<?php
namespace app\controller;

use think\Controller;
use think\facade\Request as Request;
use app\model\Admin as AdminModel;
use think\exception\PDOException;

class Account extends Controller
{
    public function initialize()
    {
        $this->AdminModel = new AdminModel();
    }
    /**
     * 管理员登陆
     * @param userName 账号
     * @param password 密码
     */
    public function logIn()
    {
        $userName = Request::post("userName");
        $password = Request::post("password");
        if ($userName == null || $password == null) {
            return msg(false, 11);
        }
        try {
            $result = $this->AdminModel->judge($userName, $password);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
        if ($result == true) {
            $errCode = 201;
            session("logIn", "1");
        } else
            $errCode = 13;
        return msg($result, $errCode);
    }
    /**
     * 注册
     * @param userName 账号
     * @param password 密码
     */
    public function signUp()
    {
        $userName = Request::post("userName");
        $password = Request::post("password");
        if ($userName == null || $password == null) {
            return msg(false, 11);
        }
        try {
            $this->AdminModel->signUp($userName, $password);
        } catch (PDOException $e) {
            return msg_exp(false, $e->getMessage(), $e->getCode());
        }
        return msg(true, 200);
    }
    /**
     * 退出登陆
     */
    public function logOut()
    {
        session(null);
        return msg(true, 200);
    }
    /**
     * 判断是否已登陆
     */
    public function isLog()
    {
        $status = session("?logIn") == true ? true : false;
        $errCode = $status == true ? 202 : 12;
        return msg($status, $errCode);
    }
}
