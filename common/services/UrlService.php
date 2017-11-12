<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/14
 * Time: 16:35
 */

namespace app\common\services;

//构建链接
use yii\helpers\Url;

class UrlService
{
    /**
     * 构建web所有的链接
     * @param $path
     * @param array $params
     * @return string
     */
    public static function buildWebUrl($path, $params = [])
    {
        $domain_config = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path], $params));
        return $domain_config['web'] . $path;
    }

    /**
     * 构建会员端的链接
     * @param $path
     * @param array $params
     */
    public static function buildMUrl($path, $params = [])
    {
        $domain_config = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path], $params));
        return $domain_config['m'] . $path;
    }

    /**
     * 构建官网的链接
     * @param $path
     * @param array $params
     * @return string
     */
    public static function buildWwwUrl($path, $params = [])
    {
        $domain_config = \Yii::$app->params['domain'];
        $path = Url::toRoute(array_merge([$path], $params));
        return $domain_config['www'] . $path;
    }

    /**
     * 空链接
     * @return string
     */
    public static function buildNullUrl()
    {
        $domain_config = \Yii::$app->params['domain'];
        return "javascript:void(0);";
    }

    /**
     * 图片地址
     * @param $bucket
     * @param $image_key
     */
    public static function buildPicUrl($bucket, $image_key)
    {
        $domain_config = \Yii::$app->params['domain'];
        $upload_config = \Yii::$app->params['upload'];
        return $domain_config['www'] . $upload_config[$bucket] . "/" . $image_key;
    }

}