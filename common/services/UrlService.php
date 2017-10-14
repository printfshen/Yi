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
        $path = Url::toRoute(array_merge([$path], $params));
        return "/web" . $path;
    }

    /**
     * 构建会员端的链接
     * @param $path
     * @param array $params
     */
    public static function buildMUrl($path, $params = [])
    {
        $path = Url::toRoute(array_merge([$path], $params));
        return "/m" . $path;
    }

    /**
     * 构建官网的链接
     * @param $path
     * @param array $params
     * @return string
     */
    public static function buildWwwUrl($path, $params = [])
    {
        $path = Url::toRoute(array_merge([$path], $params));
        return $path;
    }

    /**
     * 空链接
     * @return string
     */
    public static function buildNullUrl()
    {
        return "javascript:void(0);";
    }

}