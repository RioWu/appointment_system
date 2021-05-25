<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
/**
 * 用于返回json格式的函数调用结果
 * @param bool $status 表示方法整体的执行情况
 * @param int $errCode 表示错误代码
 * @param array $data 表示需要返回的数据，可能有
 */
function msg($staus, $errCode, $data = []): string
{
    $map = array(
        200 => "操作成功",
        201 => "身份验证成功",
        202 => "已登陆",
        11 => "非法的参数传递",
        12 => "未登录",
        13 => "用户名或密码错误",
        14 => "不可重复报名",
        15 => "该操作需要id",
        16 => "该沙龙报名人数已满",
        17 => "该沙龙不存在"
    );
    $ret = array(
        "status" => $staus,
        "errCode" => $errCode,
        "errMsg" => $map[$errCode],
    );
    if ($data != []) {
        $ret["data"] = $data;
    }
    return json_encode($ret, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES);
}
/**
 * 用于有内置errMsg提供的情况下使用
 */
function msg_exp(bool $status, $errMsg, $errCode = null): string
{
    if ($errCode == null) {
        $ret = array(
            "status" => $status,
            "errMsg" => $errMsg
        );
        return json_encode($ret, JSON_UNESCAPED_SLASHES, JSON_UNESCAPED_UNICODE);
    } else {
        $ret = array(
            "status" => $status,
            "errCode" => $errCode,
            "errMsg" => $errMsg
        );
        return json_encode($ret, JSON_UNESCAPED_UNICODE, JSON_UNESCAPED_SLASHES);
    }
}
