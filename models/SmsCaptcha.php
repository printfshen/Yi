<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms_captcha".
 *
 * @property string $id
 * @property string $mobile
 * @property string $captcha
 * @property string $ip
 * @property string $expires_at
 * @property integer $status
 * @property string $created_time
 */
class SmsCaptcha extends \yii\db\ActiveRecord
{
    /**
     * 验证手机验证码
     * @param $mobile
     * @param $captcha
     */
    public static function checkCaptcha($mobile, $captcha)
    {
        $info = self::find()->where(['mobile'=>$mobile,'captcha'=>$captcha])->one();
        if ($info && strtolower($info['expires_at']) >= time())
        {
            $info->expires_at = date("Y-m-d H:i:s", time()-1);
            $info->status = 1;
            $info->save(0);
            return true;
        }
        return false;
    }

    /**
     * 生成手机短信验证码
     * @param $mobile           手机号码
     * @param string $ip
     * @param string $sign      签名
     * @param string $channel   渠道
     */
    public function geneCustomCaptcha($mobile, $ip = "",$sign = "", $channel = "")
    {
        $this->mobile = $mobile;
        $this->ip = $ip;
        $this->captcha = rand(100000, 999999);
        $this->expires_at = date("Y-m-d H:i:s", time() + 60 * 10);
        $this->created_time = date("Y-m-d H:i:s", time());
        $this->status = 0;
        return $this->save(0);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms_captcha';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expires_at', 'status', 'created_time'], 'required'],
            [['expires_at', 'created_time'], 'safe'],
            [['status'], 'integer'],
            [['mobile', 'ip'], 'string', 'max' => 20],
            [['captcha'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'captcha' => 'Captcha',
            'ip' => 'Ip',
            'expires_at' => 'Expires At',
            'status' => 'Status',
            'created_time' => 'Created Time',
        ];
    }
}
