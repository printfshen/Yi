<?php
use app\common\services\UtiService;
use app\common\services\UrlService;
use app\common\services\StaticService;
use app\assets\MAsset;
StaticService::includeAppJsStatic("/js/m/default/index.js", MAsset::className());
?>
<div style="min-height: 500px;">
    <div class="shop_header">
        <i class="shop_icon"></i>
        <strong><?=UtiService::encode($info['name']);?></strong>
    </div>

<?php if($image_list):?>
    <div id="slideBox" class="slideBox">
        <div class="bd">
            <ul>

                <?php foreach($image_list as $_item):?>
                <li><img style="max-height: 250px;" src="<?=UrlService::buildPicUrl("brand", $_item['image_key'])?>" /></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="hd"><ul></ul></div>
    </div>
    <?php endif;?>
    <div class="fastway_list_box">
        <ul class="fastway_list">
            <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>品牌名称：<?=UtiService::encode($info['name']);?></span></a></li>
            <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>联系电话：<?=UtiService::encode($info['mobile']);?></span></a></li>
            <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>联系地址：<?=UtiService::encode($info['address']);?></span></a></li>
            <li><a href="javascript:void(0);" style="padding-left: 0.1rem;"><span>品牌介绍：<?=UtiService::encode($info['description']);?></span></a></li>
        </ul>
    </div></div>

