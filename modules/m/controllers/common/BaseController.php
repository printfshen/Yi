<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/11/13
 * Time: 23:41
 */

namespace app\modules\m\controllers\common;


use app\common\components\BaseWebController;
use app\common\services\UrlService;
use app\common\services\UtiService;
use app\models\Member;

class BaseController extends BaseWebController
{
    protected $auth_cookie_current_openid = "shop_m_openid";
    protected $auth_cookie_name = "member";
    protected $salt = "shenfengshan";
    protected $current_user = null;
    /**
     * 不需要登陆的页面
     * @var array
     */
    protected $allowAllAction = [
        'm/oauth/login',
        'm/oauth/logout',
        'm/oauth/callback',
        'm/user/bind',
    ];
    /**
     * 特殊的URL
     * 在微信中不需要登陆但是必须要获取openid
     * 如果在H5浏览器中，可以不用登陆
     * @var array
     */
    public $special_AllowAction = [
        'm/default/index',
        'm/product/index',
        'm/product/info',
    ];

    public function __construct($id,  $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = "main";

    }

    public function beforeAction($action)
    {
        $login_status = $this->checkLoginStatus();
        if (in_array($action->getUniqueId(), $this->allowAllAction))
        {
            return true;
        }

        if (!$login_status){
            if (\Yii::$app->request->isAjax)
            {
                return $this->renderJson([], "未登录，系统将引导您重新登陆~~",-302);
            } else {
                $redirect_url = UrlService::buildMUrl('/user/bind');
                if (UtiService::isWechat())
                {
                    $openid = $this->getCookie($this->auth_cookie_current_openid);
                    if ($openid)
                    {
                        if (in_array($action->getUniqueId(), $this->special_AllowAction))
                        {
                            return true;
                        }
                    } else {
                        $redirect_url = UrlService::buildMUrl('/oauth/login');
                    }
                } else {
                    if (in_array($action->getUniqueId(), $this->special_AllowAction))
                    {
                        return true;
                    }
                }
                $this->redirect($redirect_url);
            }
            return false;
        }
        return true;
    }

    /**
     * 验证登陆状态
     */
    protected function checkLoginStatus()
    {
        $auth_cookie = $this->getCookie($this->auth_cookie_name);
        if (!$auth_cookie)
        {
            return false;
        }

        list($auth_token, $member_id) = explode("#", $auth_cookie);
        if (!$auth_token || !$member_id)
        {
            return false;
        }
        if ($member_id && preg_match("/^\d+$/", $member_id)){
            $member_info = Member::findOne(['id'=>$member_id, 'status'=>1]);
            if (!$member_info)
            {
                $this->removeLoginStatus();
                return false;
            }

            if ($auth_token != $this->geneAuthToken($member_info))
            {
                $this->removeLoginStatus();
                return false;
            }

            $this->current_user = $member_info;
            \Yii::$app->view->params['current_user'] = $member_info;
            return true;
        }
        return false;
    }

    /**
     * 设置登陆状态
     * @param $user_info
     */
    public function setLoginStatus($user_info)
    {
        $auth_token = $this->geneAuthToken($user_info);
        $this->setCookie($this->auth_cookie_name, $auth_token."#".$user_info['id']);
    }

    /**
     * 移除登陆状态
     */
    protected function removeLoginStatus()
    {
        $this->removeCookie($this->auth_cookie_name);
    }

    /**
     * 获取加密
     * @param $member_info
     * @return string
     */
    public function geneAuthToken($member_info)
    {
        return md5($this->salt . "-{$member_info['id']}-{$member_info['mobile']}-{$member_info['salt']}");
    }

    public function goHome()
    {
        return $this->redirect(UrlService::buildMUrl('/default/index'));
    }
}