<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/22
 * Time: 11:29
 */

namespace app\common\services;


class ConstantMapService
{
    //微信绑定
    public static $client_type_wechat = 1;

    public static $status_default = -1;
    public static $status_mapping =[
        1 => "正常",
        0 => "已删除",
    ];
    public static $sex_mapping = [
        0 => "未知",
        1 => "男",
        2 => "女",
    ];
    public static $default_avatar = "default_avatar";

    public static $default_password = "******";

    public static $default_syserror = '系统繁忙，请稍后再试~~';
}