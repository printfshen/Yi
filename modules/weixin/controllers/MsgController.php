<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/11/29
 * Time: 23:52
 */

namespace app\modules\weixin\controllers;


use app\common\components\BaseWebController;
use app\models\AppAccessLog;

class MsgController extends BaseWebController
{
    public function actionIndex()
    {
        //加密验证
        if (!$this->checkSignature())
        {
            return "error";
        }
        if(array_key_exists("echostr", $_GET) && $_GET['echostr'])
        {
            return $_GET['echostr']; //用户微信第一次验证
        }
        return "hello world";
    }

    public function checkSignature()
    {
        $signature = $this->get("signature", "");
        $timestamp = $this->get("timestamp", "");
        $nonce = $this->get("nonce", "");

        $tmpArr = array(\Yii::$app->params['weixin']['token'], $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if($tmpStr == $signature)
        {
            return true;
        } else {
            return false;
        }
    }
}