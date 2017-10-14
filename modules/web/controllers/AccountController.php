<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class AccountController extends Controller
{
    public function __construct($id,  $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = "main";
    }

    /**
     * 账户列表
     */
    public function actionIndex()
    {
        return $this->render("index");
    }

    /**
     * 账户编辑或者添加
     */
    public function actionSet()
    {
        return $this->render("set");
    }

    /**
     * 账户详情
     */
    public function actionInfo()
    {
        return $this->render("info");
    }
}
