<?php

namespace app\modules\web\controllers;

use app\common\components\BaseWebController;
use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class DashboardController extends BaseController
{


    /**
     * 仪表盘界面
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
