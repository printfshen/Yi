<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/12/3
 * Time: 20:40
 */

namespace app\modules\m\controllers;


use app\common\components\HttpClient;
use app\common\services\UrlService;
use app\common\services\weixin\RequestService;
use app\modules\m\controllers\common\BaseController;

class OauthController extends BaseController
{
    /**
     * 微信授权页面跳转
     * @return \yii\web\Response
     */
    public function actionLogin()
    {
        $scope = $this->get("scope", "snsapi_base");
        $appid = \Yii::$app->params['weixin']['appid'];
        $redirect_uri = UrlService::buildMUrl("/oauth/callback");
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?"
            . "appid=" . $appid
            . "&redirect_uri=" . $redirect_uri
            . "&response_type=code"
            . "&scope=" . $scope
            . "&state=STATE"
            . "#wechat_redirect";
        return $this->redirect($url);
    }

    public function actionCallback()
    {
        //获取code
        $code = $this->get("code", "");
        if(!$code)
        {
            return $this->goHome();
        }
        $appid = \Yii::$app->params['weixin']['appid'];
        $appsecret = \Yii::$app->params['weixin']['appsecret'];

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?"
            . "appid=" . $appid
            . "&secret=" . $appsecret
            . "&code=" . $code
            . "&grant_type=authorization_code";
        //用过code 获取网页授权的access_token
        $ret = HttpClient::get($url);
        $ret = @json_decode($ret, true);
        $ret_token = isset($ret['access_token']);
        if (!$ret_token){
            return $this->goHome();
        }

        $openid = isset($ret['openid']);
        $scope = isset($ret['scope']);
        //拉取用户信息
        if ($scope == "snsapi_base")
        {
            $url = "https://api.weixin.qq.com/sns/userinfo?"
                . "access_token=" . $ret['access_token']
                . "&openid=" . $ret['openid']
                . "&lang=zh_CN";
            $info = HttpClient::get($url);
            $info = json_encode($info, true);
            var_dump($info);
        }

    }

}