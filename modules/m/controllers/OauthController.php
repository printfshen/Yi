<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/12/3
 * Time: 20:40
 */

namespace app\modules\m\controllers;


use app\common\components\HttpClient;
use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\common\services\weixin\RequestService;
use app\models\Member;
use app\models\OauthMemberBind;
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
        $ret_token = isset($ret['access_token']) ? $ret['access_token'] : '';

        if (!$ret_token){
            return $this->goHome();
        }

        $openid = isset($ret['openid']) ? $ret['openid'] : '';
        $scope = isset($ret['scope']) ? $ret['scope'] : '';

        //微信用户opneid 扔cookie 里面
        $this->setCookie($this->auth_cookie_current_openid, $openid);

        //判断是否绑定
        $reg_bind = OauthMemberBind::find()
            ->where(['openid'=>$openid,'type'=>ConstantMapService::$client_type_wechat])
            ->one();
        if ($reg_bind)
        {
            $member_info = Member::findOne(['id'=>$reg_bind['member_id'], 'status' => 1]);
            if (!$member_info)
            {
                $reg_bind->delete();
                return $this->goHome();
            }

            //拉取用户信息
            if ($scope == "snsapi_userinfo")
            {
                $url = "https://api.weixin.qq.com/sns/userinfo?"
                    . "access_token=" . $ret_token
                    . "&openid=" . $openid
                    . "&lang=zh_CN";

                $wechar_user_info = HttpClient::get($url);
                $wechar_user_info = @json_decode($wechar_user_info, true);
                if ($member_info['nickname'] == $member_info['mobile'])
                {
                    $member_info->nickname = isset($wechar_user_info['nickname']) ? $wechar_user_info['nickname'] : $member_info->nickname;
                    $member_info->update(0);
                }
            }
            //设置登陆状态
            $this->setLoginStatus($member_info);
        }
        return $this->redirect(UrlService::buildMUrl('/default/index'));
    }

    /**
     * 退出登陆状态
     */
    public function actionLogout()
    {
        $this->removeLoginStatus();
        $this->removeCookie($this->auth_cookie_current_openid);
        return $this->redirect(UrlService::buildMUrl('/user/bind'));
    }

}