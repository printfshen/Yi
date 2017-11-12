;
upload = {
    error:function (msg) {
        common_ops.alert(msg)
    },
    success:function (image_key) {
        // common_ops.alert(image_key)
        var html = '<img src="' + common_ops.buildPicUrl("brand", image_key) + '">' +
            '<span class="fa fa-times-circle del del_image" data="' + image_key + '">' +
            '<i></i></span>';
        // alert(html); return false;
        if($(".upload_pic_wrap .pic-each").size() > 0){
            $(".upload_pic_wrap .pic-each").html(html);
        } else {
            $(".upload_pic_wrap").append('<span class="pic-each">' + html + '</span>');
        }
        brand_set_ops.delete_img();
    }
};

var brand_set_ops = {
    init:function () {
        this.eventBind();
        this.delete_img();
    },
    eventBind:function () {
        //表单提交
        $(".wrap_brand_set .save").click(function () {

            var btn_target = $(this);
            if(btn_target.hasClass("disabled"))
            {
                common_ops.alert("正在处理请不要重复提交~~~");
                return false;
            }

            var name_target = $(".wrap_brand_set input[name=name]");
            var name = name_target.val();

            var image_key = $(".wrap_brand_set .pic-each .del_image").attr("data");

            var mobile_target = $(".wrap_brand_set input[name=mobile]");
            var mobile = mobile_target.val();

            var address_target = $(".wrap_brand_set input[name=address]");
            var address = address_target.val();

            var description_target = $(".wrap_brand_set textarea[name=description]");
            var description = description_target.val();

            if(name.length < 1)
            {
                common_ops.tip("请输入符合规范的品牌名称~~~", name_target);
                return false;
            }

            if($(".wrap_brand_set .pic-each").size()==0)
            {
                common_ops.alert("请上传品牌logo~~~");
                return false;
            }

            if(mobile.length != 11)
            {
                common_ops.tip("请输入符合规范的电话号码~~~", mobile_target);
                return false;
            }

            if(address.length < 1)
            {
                common_ops.tip("请输入符合规范的地址信息~~~", address_target);
                return false;
            }

            if(description.length < 1)
            {
                common_ops.tip("请输入符合规范的品牌介绍~~~", description_target);
                return false;
            }
            var data = {
                name : name,
                image_key : image_key,
                mobile : mobile,
                address : address,
                description : description,
            };

            $.ajax({
                url : common_ops.buildWebUrl("/brand/set"),
                data : data,
                type : 'POST',
                dataType : "json",
                success:function (res) {
                    btn_target.removeClass("disabled");
                    var callback = null;
                    if(res.code == 200)
                    {
                        callback = function () {
                            window.location.href = common_ops.buildWebUrl("/brand/info");
                        }
                    }
                    common_ops.alert(res.msg, callback);
                }
            })

        });
        //图片上传ajax
        $('.wrap_brand_set .upload_pic_wrap input[name=pic]').change(function () {
            $('.wrap_brand_set .upload_pic_wrap').submit();
        })

    },
    delete_img:function () {
        //图片删除
        $(".wrap_brand_set .del_image").unbind().click(function () {
            $(this).parent().remove();
        })
    }
};

$(document).ready(function () {
    brand_set_ops.init();
});