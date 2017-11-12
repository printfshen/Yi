<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/17
 * Time: 0:18
 */

namespace app\common\services;

use yii\helpers\Html;

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

    /**
     * 防止 htmlspecialchars  攻击
     * @param $display
     * @return string
     */
    public static function encode($display)
    {
        return Html::encode($display);
    }

    /**
     * @return mixed
     */
    public static function getRootPath()
    {
        return dirname(\Yii::$app->vendorPath);
    }
}