<?php

namespace app\modules\m\controllers;

use app\models\BrandImages;
use app\models\BrandSetting;
use app\modules\m\controllers\common\BaseController;
use yii\web\Controller;


class DefaultController extends BaseController
{
    /**
     * 品牌首页
     * @return string
     */
    public function actionIndex()
    {
        $info = BrandSetting::find()->one();
        $image_list = BrandImages::find()->orderBy(["id"=>SORT_DESC])->all();
        return $this->render('index', [
            "info" => $info,
            "image_list" => $image_list,
        ]);
    }
}
