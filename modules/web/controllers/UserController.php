<?php

namespace app\modules\web\controllers;

use app\common\components\BaseWebController;
use app\common\services\UrlService;
use app\models\User;
use app\modules\web\controllers\common\BaseController;

/**
 * Default controller for the `web` module
 */
class UserController extends BaseController
{
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = "main";
    }
    /**
     * 登陆页面
     */
    public function actionLogin()
    {
        //如果是GET请求，直接展示登陆页面
        if(\Yii::$app->request->isGet)
        {
            $this->layout = "user";
            return $this->render("login");
        }
//        var_dump($this->post(null));
        $login_name = trim($this->post("login_name"));
        $login_pwd = trim($this->post("login_pwd"));
        if(!$login_name || !$login_pwd)
        {
            return $this->renderJs("请输入正确的用户名和密码~~~1", UrlService::buildWebUrl("/user/login"));
        }
        //从 用户表 获取login_name = $login_name 信息是否存在
        $user_info = User::find()->where([
            "login_name" => $login_name,
        ])->one();
        if(!$user_info)
        {
            return $this->renderJs("请输入正确的用户名和密码~~~2", UrlService::buildWebUrl("/user/login"));
        }
        //验证密码 md5(login_pwd + md5(login_salt))
//        $auth_pwd = md5($login_pwd . md5($user_info['login_salt']));
        if($user_info->verifyPassword($login_pwd))
        {
            return $this->renderJs("请输入正确的用户名和密码~~~3", UrlService::buildWebUrl("/user/login"));
        }

        //保存用户的登陆状态
        //cookie进行保存用户登陆状态
        //加密字符串+ # + uid ， MD5(login_name + login_pwd + login+salt)
//        $auth_token = md5($user_info['login_name']
//            . $user_info['login_pwd']
//            . $user_info['login_salt']);
        $this->setLoginStatus($user_info);
        return $this->redirect(UrlService::buildWebUrl("/dashboard/index"));
    }

    /**
     * 编辑当前登陆人信息
     */
    public function actionEdit()
    {
        if(\Yii::$app->request->isGet)
        {
            //获取个人信息

            return $this->render(
                "edit",[
                    "user_info" =>$this->current_user,
                ]);
        } else {
            $nickname = trim($this->post("nickname"));
            $email = trim($this->post("email"));
            if (mb_strlen($nickname, "utf-8") < 1)
            {
                return $this->renderJson([], "请输入合法的姓名");
            }
            if (mb_strlen($email, "utf-8") < 1)
            {
                return $this->renderJson([], "请输入合法的邮箱");
            }

            $user_info =  $this->current_user;
            $user_info->nickname = $nickname;
            $user_info->email = $email;
            $user_info->updated_time = date("Y-m-d H:i:s");
            $user_info->update(0);
            return $this->renderJson([], "编辑成功~~~");
        }


    }

    /**
     * 重置当前登陆密码
     */
    public function actionResetPwd()
    {
        if(\Yii::$app->request->isGet)
        {
            return $this->render("reset_pwd",[
                "user_info" => $this->current_user,
            ]);
        }

        $old_password = trim($this->post("old_password"));
        $new_password = trim($this->post("new_password"));

        if (mb_strlen($old_password,"utf-8") < 1)
        {
            return $this->renderJson([],"请输入原密码");
        }

        if (mb_strlen($new_password,"utf-8") < 6)
        {
            return $this->renderJson([],"请输入不少于6位字符的新密码~~~");
        }

        if($old_password == $new_password)
        {
            $this->renderJson([], "请重新输入一个吧，新密码和老密码相同~~~");
        }
        //判断原密码是否正确
        $user_info = $this->current_user;
        if ($user_info->verifyPassword($old_password)){
            return $this->renderJson([],"请检查原密码是否正确~~~", -1);
        }
        $user_info->setPassword($new_password);
        $user_info->updated_time = date("Y-m-d H:i:s",time());
        $user_info->update(0);

        //修改密码后  把资料写入缓存中
        $this->setLoginStatus($user_info);

        return $this->renderJson([], "重置密码成功~~~");
    }

    /**
     * 退出登陆
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        $this->removeLoginStatus();
        return $this->redirect(UrlService::buildWebUrl("/user/login"));
    }
}
