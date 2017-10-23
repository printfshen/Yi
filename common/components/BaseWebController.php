<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/14
 * Time: 15:06
 */

namespace app\common\components;


use yii\web\Controller;
use yii\web\Cookie;

/**
 * 集成常用公用方法 提供给所有的Controller使用
 * get  post  setCookie  getCookie removeCookie renderJson
 * Class BaseWebController
 * @package app\common\components
 */
class BaseWebController extends Controller
{
    public $enableCsrfValidation = false; //关闭CSRF


    /**
     * 获取http的get参数
     * @param $key
     * @param string $default_val
     * @return array|mixed
     */
    public function get($key, $default_val = "")
    {
        return \Yii::$app->request->get($key, $default_val);
    }

    /**
     * 获取http的post的参数
     * @param $key
     * @param string $default_val
     * @return array|mixed
     */
    public function post($key ,$default_val = "")
    {
        return \Yii::$app->request->post($key, $default_val);
    }

    /**
     * 设置Cookie
     * @param $name
     * @param $value
     * @param int $expire
     */
    public function setCookie($name, $value, $expire = 0)
    {
        $cookies = \Yii::$app->response->cookies;
        $cookies->add(new Cookie([
            "name" => $name,
            "value" => $value,
            "expire" => $expire,
        ]));
    }

    /**
     * 获取Cookie
     * @param $name
     * @param string $default_val
     * @return mixed
     */
    public function getCookie($name, $default_val = "")
    {
        $cookies = \Yii::$app->request->cookies;
        return $cookies->getValue($name, $default_val);
    }

    /**
     * 删除cookie
     * @param $name
     */
    public function removeCookie($name)
    {
        $cookies = \Yii::$app->response->cookies;
        $cookies->remove($name);
    }

    public function renderJson($data = [], $msg = "ok", $code = 200)
    {
        header("Content-type:application/json");
        echo json_encode([
            "code" => $code,
            "msg" => $msg,
            "data" => $data,
            "req_id" => uniqid(),
        ]);
        return \Yii::$app->end();
    }

    //统一JS提醒
    public function renderJs($msg, $url)
    {
        return $this->renderPartial("@app/views/common/js",["msg" => $msg, "url" => $url]);
    }

}