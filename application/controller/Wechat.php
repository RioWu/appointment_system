<?php
namespace app\controller;

use think\facade\Config;
use think\Controller;
use think\facade\Request as Request;

class Wechat extends Controller
{
    public function index()
    {
        //    先初始化微信
        $app = app('wechat.official_account');
        $app->server->push(function(){
            return 'hello,world';
        });
        $app->server->serve()->send();
    }
    /**
     * 用于获取openId
     * @param code 前端传来的code参数
     */
    public function getOpenId(){
        $code = Request::post("code");
        if($code == null)
            return msg(false,11);
        $config = Config::get("wechat.official_account");   
        $config = $config["default"];
        $curl = curl_init();
        $options = array(
            CURLOPT_URL => "https://api.weixin.qq.com/sns/oauth2/access_token?"."appid=".$config["app_id"]."&secret=".$config["secret"]."&code=".$code."&grant_type=authorization_code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_NOBODY => false,
            CURLINFO_HEADER_OUT => true,
        );
        curl_setopt_array($curl, $options);
        $result = curl_exec($curl);
        return $result;
    }
}
