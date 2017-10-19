<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/17
 * Time: 0:18
 */

namespace app\common\services;

//只封装通用方法
class UtiService
{
    /**
     * 获取IP
     * HTTP_X_FORWARDED_FOR 真实IP地址
     * @return mixed
     */
    public static function getIP()
    {
        if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        return $_SERVER['REMOTE_ADDR'];

    }
}