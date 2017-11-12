<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/11/7
 * Time: 23:10
 */

namespace app\modules\web\controllers;


use app\common\services\UploadService;
use app\modules\web\controllers\common\BaseController;

class UploadController extends BaseController
{
    private  $allow_file_type = ["jpg", "gif", "jpg", "jpeg"];
    /**
     * 上传接口
     * bucket：avatar / brand / book
     *  篮子     头像     品牌    图书
     */
    public function actionPic()
    {
        $bucket = trim($this->post("bucket", ""));
        $callback = "window.parent.upload";
        if (!$_FILES || !isset($_FILES['pic']))
        {
            return "<script>{$callback}.error('请选择文件之后在提交~~~')</script>";
        }
        $file_name = $_FILES['pic']['name'];
        $tmo_file_extend = explode(".", $file_name);
        if(!in_array( strtolower(end($tmo_file_extend)), $this->allow_file_type))
        {
            return "<script>{$callback}. error('请上传制定类型的图片格式，类型允许png，gif，jpg，gpeg~~~')</script>";
        }

        //上传图片的业务逻辑 todo
        $ret = UploadService::uploadByFile($file_name, $_FILES['pic']['tmp_name'], $bucket);
        if(!$ret)
        {
            return "<script>{$callback}.error('" . UploadService::getLastErrorMsg() . "')</script>";
        }

        return "<script>{$callback} . success('{$ret['path']}')</script>";
    }

}