<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/10/15
 * Time: 21:35
 */

namespace app\modules\web\controllers\common;


use app\common\components\BaseWebController;
use app\common\services\applog\AppLogService;
use app\common\services\UrlService;
use app\models\User;

//web 统一控制器当中会做一些web独有的验证
//1.制定特定的布局文件

class BaseController extends BaseWebController
{
    protected $auth_cookie_name = "mooc_book";
    //当前登陆用户信息
    public $current_user = null;
    //允许的控制器方法路径
    public $allowAllAction = [
        "web/user/login",
    ];

    public function __construct($id,  $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = "main";
    }

    /**
     * 登陆状态统一验证
     * @param \yii\base\Action $action
     */
    public function beforeAction($action)
    {
        //验证是否登陆
        $is_login = $this->checkLoginStatus();

        if(in_array($action->getUniqueId(), $this->allowAllAction))
        {
            return true;
        }
        if(!$is_login)
        {
            if(\Yii::$app->request->isAjax)
            {
                $this->renderJson([], "未登录，请先登陆~~~", "-302");
            } else {
                $this->redirect(UrlService::buildWebUrl("/user/login"));
            }
        }
        //记录用户所有的访问操作
        AppLogService::addAppAccessLog($this->current_user['uid']);


        return true;
    }

    /**
     * 验证当前登陆状态是否有效
     * @return bool
     */
    private function checkLoginStatus()
    {
        $auth_cookie = $this->getCookie($this->auth_cookie_name, "");
        if(!$auth_cookie)
        {
            return false;
        }

        list($auth_token, $uid) = explode("#", $auth_cookie );
        if(!$auth_cookie || !$uid)
        {
            return false;
        }

        if(!preg_match("/^\d+$/", $uid))
        {
            return false;
        }

        $user_info = User::find()->where([
            "uid" => $uid,
        ])->one();
        if(!$user_info)
        {
            return false;
        }

        if($auth_token != $this->geneAuthToken($user_info))
        {
            return false;
        }
        $this->current_user = $user_info;
        return true;
    }

    /**
     * 重置当前登陆的密码
     * @param $user_info
     */
    public function setLoginStatus($user_info)
    {
        $auth_token = $this->geneAuthToken($user_info);
        $this->setCookie($this->auth_cookie_name, $auth_token. "#" . $user_info['uid']);
    }

    /**
     * 移除登陆状态
     */
    public function removeLoginStatus()
    {
        $this->removeCookie($this->auth_cookie_name);
    }

    /**
     * 统一生产加密字符串
     * 加密字符串+ # + uid ， MD5(login_name + login_pwd + login+salt)
     * @param $user_info
     */
    public function geneAuthToken($user_info)
    {
        return md5($user_info['login_name'] . $user_info['login_pwd'] . $user_info['login_salt']);
    }
}