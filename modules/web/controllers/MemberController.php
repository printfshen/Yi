<?php

namespace app\modules\web\controllers;

use app\common\services\ConstantMapService;
use app\common\services\UrlService;
use app\common\services\UtiService;
use app\models\Member;
use app\modules\web\controllers\common\BaseController;
use yii\web\Controller;

/**
 * Default controller for the `web` module
 */
class MemberController extends BaseController
{
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->layout = "main";
    }

    /**
     * 会员列表
     */
    public function actionIndex()
    {
        $mix_kw = trim($this->get("mix_kw", ""));
        $status = intval($this->get("status", ConstantMapService::$status_default));
        $p = intval($this->get("p", 1));
        $p = ($p > 0) ? $p : 1;
        $query = Member::find();
        if($mix_kw)
        {
            $where_nickname = [
                'LIKE', 'nickname',
                '%' . strtr($mix_kw, ['%' => '\%', '_'=>'\_', '\\' => '\\\\']) . '%', false];
            $where_mobile = [
                'LIKE', 'mobile',
                '%' . strtr($mix_kw, ['%' => '\%', '_'=>'\_', '\\' => '\\\\']) . '%', false];
            $query->andWhere(['OR', $where_nickname, $where_mobile]);
        }

        if($status > ConstantMapService::$status_default)
        {
            $query->andWhere(['status'=>$status]);
        }

        //分页功能，巫妖两个参数  1：符合条件的总记录数 2：每页显示的数量
        $page_size = 50; //每页多少
        $total_res_count = $query->count(); //总记录条数
        $total_page = ceil($total_res_count/$page_size); //总页数

        $list = $query->orderBy(["id"=>SORT_DESC])
            ->offset(($p-1)*$page_size)
            ->limit($page_size)
            ->all();

        $data = [];
        if($list)
        {
            foreach ($list as $_item)
            {
                $data[] = [
                    'id' => UtiService::encode($_item['id']),
                    'nickname' => UtiService::encode($_item['nickname']),
                    'mobile' => UtiService::encode($_item['mobile']),
                    'sex_desc' => ConstantMapService::$sex_mapping[$_item['sex']],
                    'avatar' => UrlService::buildPicUrl("avatar", $_item['avatar']),
                    'status_desc' => ConstantMapService::$status_mapping[$_item['status']],
                    'status' => $_item['status'],
                ];
            }
        }

        return $this->render('index', [
            "list" => $data,
            "search_conditions" => [
                'mix_kw' => $mix_kw,
                'p' => $p,
                'status' => $status,
            ],
            "pages" =>[
                'total_count' => $total_res_count,
                'page_size' => $page_size,
                'total_page' => $total_page,
                'p' => $p,
            ]
        ]);
    }

    /**
     * 会员详情
     */
    public function actionInfo()
    {
        $id = $this->get("id", 0);
        $reback_url = UrlService::buildWebUrl("/member/index");
        if(!$id)
        {
            return $this->redirect($reback_url);
        }
        $info = Member::find()->where(["id" => $id])->one();
        if(!$info)
        {
            return $this->redirect($reback_url);
        }
        return $this->render('info', [
            'info' => $info,
        ]);
    }

    /**
     * 添加或者编辑会员
     */
    public function actionSet()
    {
        if (\Yii::$app->request->isGet)
        {
            $id = intval($this->get("id", 0));
            $info = [];
            if ($id)
            {
                $info = Member::find()->where(["id"=>$id])->one();
            }
            return $this->render("set",[
                "info" => $info,
            ]);
        }


        $id = intval($this->post("id", 0));
        $nickname = trim($this->post("nickname", ""));
        $mobile = floatval($this->post("mobile", 0));
        $date_now = date("Y-m-d H:i:s");

        if( mb_strlen( $nickname,"utf-8" ) < 1 ){
            return $this->renderJSON([],"请输入符合规范的姓名~~",-1);
        }

        if( mb_strlen( $mobile,"utf-8" ) < 1   ){
            return $this->renderJSON([],"请输入符合规范的手机号码~~",-1);
        }

        $info = [];
        if($id)
        {
            $info= Member::findOne(["id" => $id]);
        }

        if ($info)
        {
            $model_member = $info;
        } else {
            $model_member = new Member();
            $model_member->status = 1;
            $model_member->avatar = ConstantMapService::$default_avatar;
            $model_member->created_time = $date_now;
        }

        $model_member->nickname = $nickname;
        $model_member->mobile = $mobile;
        $model_member->updated_time = $date_now;

        $model_member->save(0);

        return $this->renderJSON([],"操作成功~~");
    }

    /**
     * 用户改变状态
     */
    public function actionOps()
    {
        if(!\Yii::$app->request->isPost)
        {
            return $this->renderJson([], ConstantMapService::$default_syserror, -1);
        }
        $id = $this->post("id", []);
        $act = trim($this->post("act", ""));
        if(!$id)
        {
            return $this->renderJson([], "请选择要操作的会员账号~~~");
        }

        if (!in_array($act, ["remove", "recover"]))
        {
            return $this->renderJson([], "操作有误，请重试~~~", -1);
        }

        $info = Member::find()->where(["id"=>$id])->one();
        if(!$info)
        {
            return $this->renderJson([], "指定会员账号不存在~~~", -1);
        }

        switch ($act)
        {
            case "remove":
                $info->status = 0;
                break;
            case "recover":
                $info->status = 1;
                break;
        }
        $info->updated_time = date("Y-m-d H:i:s", time());
        $info->update(0);
        return $this->renderJson([], "操作成功~~~");

    }


    /**
     * 会员评论列表
     */
    public function actionComment()
    {
        return $this->render("comment");
    }
}
