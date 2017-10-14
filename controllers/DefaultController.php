<?php

namespace app\controllers;

use app\common\components\BaseWebController;

class DefaultController extends BaseWebController
{
  public function actionIndex()
  {
      //去除统一的头部
//      $this->layout = false;
      return $this->render( "index" );
  }
}
