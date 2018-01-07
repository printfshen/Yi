<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\models\BookCat;
use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class BookController extends BaseController
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
        $status = intval($this->get('status', ConstantMapService::$status_default));
        $query = BookCat::find();

        if ($status > ConstantMapService::$status_default)
        {
            $query->where(['status'=>$status]);
        }

        $list = $query->orderBy(['weight'=>SORT_DESC, 'id' => SORT_DESC])->all();

        return $this->render('cat',[
            'list' => $list,
            'status_mapping' => ConstantMapService::$status_mapping,
            'search_conditions' => [
                'status' => $status,
            ]
        ]);
    }

    /**
     * 图书分类的编辑或者添加
     */
    public function actionCat_set()
    {
        if (\Yii::$app->request->isGet)
        {
            $id = intval($this->get('id', 0));
            $info = [];
            if ($id)
            {
                $info = BookCat::find()->where(['id'=>$id])->one();
            }
            return $this->render('cat_set',[
                'info' => $info,
            ]);
        }

        $id = intval($this->post('id', 0));
        $weight = intval($this->post('weight', 1));
        $name = trim($this->post('name', ''));
        $date_now = date('Y-m-d H:i:s');

        if (mb_strlen($name, 'utf-8') < 1)
        {
            return $this->renderJson([], '请输入符合规范的分类名称~~', -1);
        }

        $has_in = BookCat::find()->where(['name'=>$name])->andWhere(['!=', 'id', $id])->count();
        if ($has_in)
        {
            return $this->renderJson([], '该分类名称已存在~~', -1);
        }

        $cat_info = BookCat::find()->where(['id'=>$id])->one();
        if ($cat_info)
        {
            $model_cook_cat = $cat_info;
        } else {
            $model_cook_cat = new BookCat();
            $model_cook_cat->created_time = $date_now;
        }
        $model_cook_cat->name = $name;
        $model_cook_cat->weight = $weight;
        $model_cook_cat->updated_time = $date_now;
        $model_cook_cat->save(0);

        return $this->renderJson([], '操作成功~~');
    }
}
