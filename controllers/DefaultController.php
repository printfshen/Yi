<?php

namespace app\controllers;

use app\common\components\BaseWebController;
use app\common\services\captcha\ValidateCode;
use app\common\services\ConstantMapService;
use app\common\services\UtiService;
use app\models\SmsCaptcha;

class DefaultController extends BaseWebController
{
    public function actionIndex()
    {
      return $this->render( "index" );
    }

    private $captcha_cookie_name = 'validate_code';
    /**
     *  获取验证码
     */
    public function actionImg_captcha()
    {
        $font_path = \Yii::$app->getBasePath()."/web/fonts/captcha.ttf";
        $captcha_handle = new ValidateCode($font_path);
        $captcha_handle->doimg();
        $this->setCookie($this->captcha_cookie_name, $captcha_handle->getCode());
    }

    /**
     * 获取手机短信验证码
     */
    public function actionGet_captcha()
    {
        $mobile = $this->post("mobile", "");
        $img_captcha = $this->post("img_captcha", "");

        if(!$mobile || !preg_match('/^1[0-9]{10}$/', $mobile)){
            $this->removeCookie($this->captcha_cookie_name);
            return $this->renderJson([],"请输入符合要求的手机号码~~", -1);
        }

        $captcha_code = $this->getCookie($this->captcha_cookie_name);
        if(strtolower($img_captcha) != $captcha_code){
            $this->removeCookie($this->captcha_cookie_name);
            return $this->renderJson([], "请输入正确的验证码~~\r\n您输入的验证码是{$img_captcha},正确的是{$captcha_code}", -1);
        }

        //发送手机验证码 能发验证码 能验证
        $model_sms = new SmsCaptcha();
        $model_sms->geneCustomCaptcha($mobile, UtiService::getIP());
        $this->removeCookie($this->captcha_cookie_name);
        if ($model_sms)
        {
            return $this->renderJson([], "发送成功,短信验证码是" . $model_sms->captcha);
        }
        return $this->renderJson([], ConstantMapService::$default_syserror, -1);

    }
}
