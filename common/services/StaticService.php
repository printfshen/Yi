<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/18
 * Time: 23:55
 */

namespace app\common\services;

//use Yii;
//只用于价值应用本身的资源文件
class StaticService
{
    public static function includeAppJsStatic($path, $depend)
    {
        self::includeAppStatic("js", $path, $depend);
    }

    public static function includeAppCssStatic($path, $depend)
    {
        self::includeAppStatic("css", $path, $depend);
    }

    protected static function includeAppStatic($type, $path, $depend)
    {
        $release_version = defined("RELEASE_VERSION") ? RELEASE_VERSION : time();
        $path = $path . "?ver=".$release_version;
        if($type == "js"){
            \Yii::$app->getView()->registerJsFile($path , ["depends" => $depend]);

        } elseif ($type == "css" ) {
            \Yii::$app->getView()->registerCssFile($path , ["depends" => $depend]);
        }

    }
}