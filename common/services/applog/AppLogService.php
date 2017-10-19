<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/17
 * Time: 0:14
 */

namespace app\common\services\applog;


use app\common\services\UtiService;
use app\models\AppLog;

class AppLogService
{
    /**
     * 记录错误日志
     * @param $appname
     * @param $content
     */
    public static function addErrorLog($appname, $content)
    {
        $error = \Yii::$app->errorHandler->exception;
        $model_app_log = new AppLog();
        $model_app_log->app_name = $appname;
        $model_app_log->content = $content;
        //获取IP，$_SERVER['REMOTE_ADDR'];
        $model_app_log->ip = UtiService::getIP();
        /**HTTP_USER_AGENT是用来检查浏览页面的访问者在用
        什么操作系统（包括版本号）浏览器（包括版本号）和用户个人偏好的代码。*/
        if(!empty($_SERVER['HTTP_USER_AGENT']))
        {
            $model_app_log->ua = $_SERVER['HTTP_USER_AGENT'];
        }

        if($error)
        {
            $model_app_log->err_code = $error->getCode();
            if(isset($error->statusCode))
            {
                $model_app_log->http_code = $error->statusCode;
            }
            if(method_exists($error, "getName"))
            {
                $model_app_log->err_name = $error->getName();
            }
            $model_app_log->created_time = date("Y-m-d H:i:s");
            $model_app_log->save(0);
        }
    }
}