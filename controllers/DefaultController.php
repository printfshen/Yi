<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class DefaultController extends Controller
{
  public function actionIndex()
  {
      //去除统一的头部
      $this->layout = false;
      return $this->render( "index" );
  }
}
