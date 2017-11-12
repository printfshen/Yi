<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\models\BrandImages;
use app\models\BrandSetting;
use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;


class BrandController extends BaseController
{
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = "main";
    }
    /**
     * 品牌详情
     */
    public function actionInfo()
    {
        $info = BrandSetting::find()->one();

        return $this->render('info', [
            "info" => $info,
        ]);
    }

    /**
     * 品牌编辑
     */
    public function actionSet()
    {
        if(\Yii::$app->request->isGet)
        {
            $info = BrandSetting::find()->one();
            return $this->render('set', [
                "info" => $info,
            ]);
        }

        $name = trim($this->post("name", ""));
        $image_key = trim($this->post("image_key", ""));
        $mobile = trim($this->post("mobile", ""));
        $address = trim($this->post("address", ""));
        $description = trim($this->post("description", ""));
        $data_now = date("Y-m-d H:i:s");


        if(mb_strlen($name, "utf-8") < 1)
        {
            return $this->renderJson([], "请输入符合规范的品牌名称~~~", -1);
        }
        if(!$image_key){
            return $this->renderJson([], "请上传品牌logo~~~");
        }

        if(mb_strlen($mobile, "utf-8") != 11)
        {
            return $this->renderJson([], "请输入符合规范的电话号码~~~", -1);
        }
        if(mb_strlen($address, "utf-8") < 1)
        {
            return $this->renderJson([], "请输入符合规范的地址信息~~~", -1);
        }
        if(mb_strlen($description) < 1)
        {
            return $this->renderJson([], "请输入符合规范的品牌介绍~~~", -1);
        }

        $info = BrandSetting::find()->one();
        if($info)
        {
            $model_brand = $info;
        } else {
            $model_brand = new BrandSetting();
            $model_brand->created_time = $data_now;
        }
        $model_brand->name = $name;
        $model_brand->logo = $image_key;
        $model_brand->mobile = $mobile;
        $model_brand->address = $address;
        $model_brand->address = $description;
        $model_brand->updated_time = $data_now;
        $model_brand->save(0);
        return $this->renderJson([], '操作成功~~~');
    }

    /**
     * 品牌相册
     */
    public function actionImages()
    {
        $list = BrandImages::find()->orderBy(["id" => SORT_DESC])->all();
        return $this->render('images', [
            "list" => $list,
        ]);
    }

    /**
     * 品牌相册图片上传
     */
    public function actionSetImage()
    {
        $image_key = trim($this->post("image_key", ""));
        if(!$image_key) return $this->renderJson([], "请上传图片之后在提交~~~", -1);

        $total_count = BrandImages::find()->count();
        if($total_count >=5 ) return $this->renderJson([], "最多上传五张", -1);

        $model = new BrandImages();
        $model->image_key = $image_key;
        $model->created_time = date("Y-m-d H:i:s");
        $model->save(0);
        return $this->renderJson([], "操作成功~~~");
    }

    /**
     * 品牌图片删除
     */
    public function actionImageOps()
    {
        if(!\Yii::$app->request->isPost) return $this->renderJson([], ConstantMapService::$default_syserror, -1);

        $id = $this->post('id', []);
        if(!$id) return $this->renderJson([], "请选择要删除的图片~~~");

        $info = BrandImages::find()->where(["id" => $id])->one();
        if(!$info) return $this->renderJson([], "指定图片不存在~~~", -1);
        $info->delete();
        return $this->renderJson([], "操作成功~~~");
    }
}
