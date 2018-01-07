<?php

namespace app\modules\m\controllers;

use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\common\services\UtiService;
use app\models\Member;
use app\models\OauthAccessToken;
use app\models\OauthMemberBind;
use app\models\SmsCaptcha;
use app\modules\m\controllers\common\BaseController;
use yii\web\Controller;

/**
 * Default controller for the `m` module
 */
class UserController extends BaseController
{
    /**
     * 账号绑定
     */
    public function actionBind()
    {
        if (\Yii::$app->request->isGet)
        {
            var_dump($this->getCookie($this->auth_cookie_current_openid));
            return $this->render("bind");
        }

        $mobile = trim($this->post("mobile"));
        $img_captcha = trim($this->post("img_captcha"));
        $captcha_code = trim($this->post("captcha_code"));

        $openid = $this->getCookie($this->auth_cookie_current_openid);
        $date_new = date("Y-m-d H:i:s");

        if (mb_strlen($mobile, 'utf-8') != 11 || !preg_match('/^[1-9]\d{10}/', $mobile))
        {
            return $this->renderJson([], "请输入符合要求的手机号码~~", -1);
        }

        if (mb_strlen($img_captcha, "utf-8") != 4)
        {
            return $this->renderJson([], "请输入符合要求的验证码~~", -1);
        }

        if (mb_strlen($captcha_code, "utf-8") != 6  || !SmsCaptcha::checkCaptcha($mobile, $captcha_code))
        {
            return $this->renderJson([], "请输入正确的短信验证码~~", -1);
        }

        $member_info = Member::find()->where(['mobile' => $mobile])->one();

        if (!$member_info)
        {
            $model_member = new Member();
            $model_member->nickname = $mobile;
            $model_member->mobile = $mobile;
            $model_member->setSalt();
            $model_member->avatar = ConstantMapService::$default_avatar;
            $model_member->reg_ip = sprintf("%u", ip2long(UtiService::getIP()));
            $model_member->status = 1;
            $model_member->created_time = $model_member->updated_time = date("Y-m-d H:i:s");
            $model_member->save(0);
        }

        if($member_info && !$member_info['status'])
        {
            return $this->renderJson([], "账号已经呗禁止，请练习客服解决~~", -1);
        }

        if ($openid)
        {
            $bind_info = OauthMemberBind::find()->where(['member_id' => $member_info['id'],
                'openid'=>$openid,
                'type' => ConstantMapService::$client_type_wechat])
                ->one();

            if(!$bind_info)
            {
                $model_bind = new OauthMemberBind();
                $model_bind->member_id = $member_info['id'];
                $model_bind->client_type = 'weixin';
                $model_bind->type = ConstantMapService::$client_type_wechat;
                $model_bind->openid = $openid;
                $model_bind->unionid = "";
                $model_bind->extra = "";
                $model_bind->updated_time = $model_bind->created_time = $date_new;
                $model_bind->save(0);
            }
        }
        if (UtiService::isWechat() && $member_info['avatar'] = ConstantMapService::$default_avatar)
        {
            return $this->renderJson(['url'=>UrlService::buildMUrl('/oauth/login', ['scope'=>'snsapi_userinfo'])], "绑定成功~~");
        }
//        todo 设置登录状态
        $this->setLoginStatus($member_info);
        return $this->renderJson(['url'=>UrlService::buildMUrl('/default/index')], "绑定成功~~");
    }

    /**
     * 我的购物车
     */
    public function actionCart()
    {
        return $this->render("cart");
    }

    /**
     * 我的订单列表
     * @return string
     */
    public function actionOrder()
    {
        return $this->render('order');
    }

    /**
     * 我的会员中心
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 我的地址列表
     * @return string
     */
    public function actionAddress()
    {
        return $this->render('address');
    }

    /**
     * 添加或者编辑收货地址
     * @return string
     */
    public function actionAddress_set()
    {
        return $this->render('address_set');
    }

    /**
     * 我的收藏
     * @return string
     */
    public function actionFav()
    {
        return $this->render('fav');
    }

    /**
     * 我的评论列表
     * @return string
     */
    public function actionComment()
    {
        return $this->render('comment');
    }

    /**
     * 我要评论
     * @return string
     */
    public function actionComment_set()
    {
        return $this->render('comment_set');
    }



}
