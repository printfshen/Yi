<?php

namespace app\modules\m\controllers;

use app\modules\m\controllers\common\BaseController;
use yii\web\Controller;


class ProductController extends BaseController
{
    /**
     * 商品列表
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 商品详情
     */
    public function actionInfo()
    {
        return $this->render('info');
    }

    /**
     * 用户下单
     * @return string
     */
    public function actionOrder()
    {
        return $this->render('order');
    }
}
