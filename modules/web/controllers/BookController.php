<?php

namespace app\modules\web\controllers;

use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class BookController extends Controller
{
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = "main";
    }

    /**
     * 图书列表
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 图书编辑或者添加
     */
    public function actionSet()
    {
        return $this->render('set');
    }

    /**
     * 图书详情
     */
    public function actionInfo()
    {
        return $this->render('info');
    }

    /**
     * 图书图片资源管理
     */
    public function actionImages()
    {
        return $this->render('images');
    }

    /**
     * 图书分类列表
     */
    public function actionCat()
    {
        return $this->render('cat');
    }

    /**
     * 图书分类的编辑或者添加
     */
    public function actionCat_set()
    {
        return $this->render('cat_set');
    }
}
