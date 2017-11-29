<?php
use app\common\services\StaticService;
use app\assets\WebAsset;
use app\common\services\UrlService;
StaticService::includeAppJsStatic("/js/web/member/index.js", WebAsset::className());
?>
<?=Yii::$app->view->renderFile("@app/modules/web/views/common/tab_member.php", ['current' => 'index'])?>

        <div class="row">
            <div class="col-lg-12">
                <form class="form-inline wrap_search">
                    <div class="row  m-t p-w-m">
                        <div class="form-group">
                            <select name="status" class="form-control inline">
                                <option value="-1">请选择状态</option>
                                <option value="1"  >正常</option>
                                <option value="0"  >已删除</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" name="mix_kw" placeholder="请输入关键字" class="form-control" value="<?=$search_conditions['mix_kw']?>">
                                <span class="input-group-btn">
                            <button type="button" class="btn  btn-primary search">
                                <i class="fa fa-search"></i>搜索
                            </button>
                        </span>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="col-lg-12">
                            <a class="btn btn-w-m btn-outline btn-primary pull-right" href="/web/member/set">
                                <i class="fa fa-plus"></i>会员
                            </a>
                        </div>
                    </div>

                </form>
                <table class="table table-bordered m-t">
                    <thead>
                    <tr>
                        <th>头像</th>
                        <th>姓名</th>
                        <th>手机</th>
                        <th>性别</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($list as $_item):?>
                    <tr>
                        <td><img alt="image" class="img-circle" src="<?=$_item['avatar']?>" style="width: 40px;height: 40px;"></td>
                        <td><?=$_item['nickname']?></td>
                        <td><?=$_item['mobile']?></td>
                        <td><?=$_item['sex_desc']?></td>
                        <td><?=$_item['status_desc']?></td>
                        <td>
                            <a  href="/web/member/info?id=<?=$_item['id']?>">
                                <i class="fa fa-eye fa-lg"></i>
                            </a>
                            <a class="m-l" href="/web/member/set?id=<?=$_item['id']?>">
                                <i class="fa fa-edit fa-lg"></i>
                            </a>

                            <?php if($_item['status'] == 1):?>
                                <a class="m-l remove" href="javascript:void(0);" data="<?=$_item['id']?>">
                                    <i class="fa fa-trash fa-lg"></i>
                                </a>
                            <?php else:?>
                                <a class="m-l recover" href="javascript:void(0);" data="<?=$_item['id']?>">
                                    <i class="fa fa-rotate-left fa-lg"></i>
                                </a>
                            <?php endif;?>
                        </td>
                    </tr>
                    <?php endforeach;?>


                    </tbody>
                </table>
                <div class="row">
                    <div class="col-lg-12">
                        <span class="pagination_count" style="line-height: 40px;">共<?=$pages['total_count']?>条记录 | 每页<?=$pages['page_size']?>条</span>
                        <ul class="pagination pagination-lg pull-right" style="margin: 0 0 ;">
                            <?php for ($_page = 1;$_page <= $pages['total_page']; $_page++):?>
                                <?php if($_page == $pages['p']):?>
                                    <li class="active">
                                        <a href="<?=UrlService::buildNullUrl();?>"><?=$_page;?></a>
                                    </li>
                                <?php else:?>
                                    <li class="">
                                        <a href="<?=UrlService::buildWebUrl("/account/index", ['p' => $_page])?>"><?=$_page;?></a>
                                    </li>
                                <?php endif;?>
                            <?php endfor;?>
                        </ul>
                    </div>
                </div>

            </div>
        </div>


