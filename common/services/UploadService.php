<?php
/**
 * Created by PhpStorm.
 * User: 沈枫山
 * Date: 2017/11/8
 * Time: 21:21
 */

namespace app\common\services;

/**
 * 上传服务
 * Class UploadService
 * @package app\common\services
 */
class UploadService extends BaseService
{
    /**
     * 根据文件路径进行上传
     * @param $file_name 上传文件名称
     * @param $file_path 上传文件的路径（缓存路径）
     * @param string $bucket 上传文件存放的类型 位置
     */
    public static function uploadByFile($file_name, $file_path, $bucket='')
    {
        if(!$file_name)
        {
            return self::_err("参数文件名是必须的~~~");
        }
        if(!$file_path || !file_exists($file_path))
        {
            return self::_err("请输入合法的参数file_path~~~");
        }
        $upload_config = \Yii::$app->params['upload'];
        if(!array_key_exists($bucket, $upload_config))
        {
            return self::_err("指定参数bucket错误~~~");
        }
        //获取文件名后缀
        $tmp_file_extend = explode(".", $file_name);
        $file_type = strtolower(end($tmp_file_extend));

        //在每个篮子下面 按照日期放图片
        $hash_key = md5(file_get_contents($file_path));
        $upload_dir_path = UtiService::getRootPath() . "/web" . $upload_config[$bucket];

        $folder_name = date("Ymd");
        $upload_dir = $upload_dir_path . "/" . $folder_name;
        if(!file_exists($upload_dir))
        {
            mkdir($upload_dir, 0777);
            chmod($upload_dir, 0777);
        }
        $upload_full_name = $folder_name . "/" . $hash_key . "." . $file_type;

        if(is_uploaded_file($file_path))
        {
            move_uploaded_file($file_path, $upload_dir_path . "/" . $upload_full_name);
        } else {
            file_put_contents($upload_dir_path . "/" . $upload_full_name, file_get_contents($file_path));
        }
        return [
            "code" => 200,
            "path" => $upload_full_name,
            "prefix" => $upload_config[$bucket],
        ];
    }
}