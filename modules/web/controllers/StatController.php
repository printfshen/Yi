<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class StatController extends Controller
{

    /**
     * 财务统计
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = false;
        return $this->render('index');
    }

    /**
     * 商品售卖统计
     * @return string
     */
    public function actionProduct()
    {
        $this->layout = false;
        return $this->render('product');
    }

    /**
     * 会员消费统计
     * @return string
     */
    public function actionMember()
    {
        $this->layout = false;
        return $this->render('member');
    }

    /**
     * 分享统计
     * @return string
     */
    public function actionShare()
    {
        $this->layout = false;
        return $this->render('share');
    }
}
