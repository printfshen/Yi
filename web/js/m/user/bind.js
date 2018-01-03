;
var user_bind_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        var that = this;


        $(".login_form_wrap .get_captcha").click(function () {
            var btn_target = $(this);
            if(btn_target.hasClass("disabled")){
                alert("正在处理，请不要重复提交~~");
                return;
            }

            var mobile = $(".login_form_wrap input[name=mobile]").val();
            var img_captcha = $(".login_form_wrap input[name=img_captcha]").val();

            if(mobile.length != 11 ||  !/^[1-9]\d{10}$/.test( mobile )){
                alert("请输入符合要求的手机号码~~");
                return false;
            }

            if(img_captcha.length != 4){
                alert("请输入正确的验证码~~");
                return false;
            }
            btn_target.addClass("disabled");

            $.ajax({
                url:"/default/get_captcha",
                data:{
                    'mobile':mobile,
                    'img_captcha':img_captcha,
                    'source':'wechat'
                },
                type:"post",
                dataType:"json",
                success:function (res) {
                    //由于是验证，没有短信通道，直接告知验证码多少了
                    alert( res.msg );
                    if(res.code == 200){
                        that.lightenOrDisabled("countdown");
                    }else{

                        $("#img_captcha").click();
                        that.lightenOrDisabled("light");
                    }
                }
            })
        })
    }
};
$(document).ready(function () {
    user_bind_ops.init();
})