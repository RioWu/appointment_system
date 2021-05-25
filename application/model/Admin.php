<?php
namespace app\model;

use think\Model;

class Admin extends model
{
    /**
     * 用于判断账户名和密码是否正确
     * @param userName 账户名
     * @param password 密码
     */
    public function judge($userName, $password)
    {
        $a = new Admin();
        $result = $a->where("user_name", $userName)->where("password", $password)->count();
        if ($result == 0)
            return false;
        else
            return true;
    }
    /**
     * 用于创建账号
     * @param userName 账户名
     * @param passWord 密码
     */
    public function signUp($userName, $password)
    {
        $a = new Admin();
        $a->user_name = $userName;
        $a->password = $password;
        $a->save();
    }
}
