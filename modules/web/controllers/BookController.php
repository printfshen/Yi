<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\models\Book;
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
        $mix_kw = trim($this->get("mix_kw", ""));
        $status = intval($this->get('status', ConstantMapService::$status_default));
        $cat_id = intval($this->get('cat_id', 0));
        $p = intval($this->get('p', 1));
        $p = ($p > 1) ? $p : 1;

        $query = Book::find();
        //拼接查询条件
        if( $mix_kw ){
            $where_name = [ 'LIKE','name','%-'.strtr($mix_kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'-%', false ];
            $where_tags = [ 'LIKE','tags','%'.strtr($mix_kw,['%'=>'\%', '_'=>'\_', '\\'=>'\\\\']).'%', false ];
            $query->andWhere([ 'OR',$where_name,$where_tags ]);
        }

        if( $status > ConstantMapService::$status_default ){
            $query->andWhere([ 'status' => $status ]);
        }

        if( $cat_id ){
            $query->andWhere([ 'cat_id' => $cat_id ]);
        }

        //分页功能,需要两个参数，1：符合条件的总记录数量  2：每页展示的数量
        //60,60 ~ 11,10 - 1
        $total_res_count = $query->count();
        $total_page = ceil($total_res_count/$this->page_size);

        $list = $query->orderBy(['id' => SORT_DESC])
            ->offset(($p-1) * $this->page_size)
            ->limit($this->page_size)
            ->all();
        $cat_mapping = BookCat::find()->orderBy(['id' => SORT_DESC])->indexBy('id')->all();

        $data = [];

        if ($list){
            foreach ($list as $_item)
            {
                foreach( $list as $_item ){
                    $tmp_cat_info = isset( $cat_mapping[ $_item['cat_id'] ] )?$cat_mapping[ $_item['cat_id'] ]:[];
                    $data[] = [
                        'id' => $_item['id'],
                        'name' => UtiService::encode( $_item['name'] ),
                        'price' => UtiService::encode( $_item['price'] ),
                        'stock' => UtiService::encode( $_item['stock'] ),
                        'tags' => UtiService::encode( $_item['tags'] ),
                        'status' => UtiService::encode( $_item['status'] ),
                        'cat_name' => $tmp_cat_info?UtiService::encode( $tmp_cat_info['name'] ):''
                    ];
                }
            }
        }

        return $this->render('index',[
            'list' => $data,
            'search_conditions' => [
                'mix_kw' => $mix_kw,
                'p' => $p,
                'status' => $status,
                'cat_id' => $cat_id
            ],
            'status_mapping' => ConstantMapService::$status_mapping,
            'cat_mapping' => $cat_mapping,
            'pages' => [
                'total_count' => $total_res_count,
                'page_size' => $this->page_size,
                'total_page' => $total_page,
                'p' => $p
            ]
        ]);
    }

    /**
     * 图书编辑或者添加
     */
    public function actionSet()
    {
        if (\Yii::$app->request->isGet)
        {
            $id = intval($this->get('id', 0));
            $info = [];
            if ($id)
            {
                $info = Book::find()->where(['id'=>$id])->one();
            }
            $cat_list = BookCat::find()->orderBy(['id'=>SORT_DESC])->all();
            return $this->render('set', [
                'cat_list' => $cat_list,
                'info' => $info,
            ]);
        }



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

    /**
     * 图书分类删除 恢复
     */
    public function actionCat_ops()
    {
        if(!\Yii::$app->request->isAjax)
        {
            return $this->renderJson([], ConstantMapService::$default_syserror, -1);
        }

        $id = $this->post('id', []);
        $act = trim($this->post('act', ''));

        if (!$id)
        {
            return $this->renderJson([], '请选择你需要操作的分类~~', -1);
        }

        $info = BookCat::find()->where(['id'=>$id])->one();
        if (!$info)
        {
            return $this->renderJson([], '指定的分类不存在~~', -1);
        }

        if (!in_array($act, ['remove', 'recover']))
        {
            return $this->renderJson([], '操作有误,请重试~~',-1);
        }

        switch ($act)
        {
            case 'remove':
                $info->status = 0;
                break;
            case 'recover':
                $info->status = 1;
                break;
        }
        $info->updated_time = date('Y-m-d H:i:s');
        $info->update(0);
        return $this->renderJson([], '操作成功~~');
    }
}
