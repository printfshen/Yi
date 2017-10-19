;
var user_edit_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        $(".save").click(function () {
            //添加防止误多次点击
            var btn_target = $(this);
            if(btn_target.hasClass("disabled"))
            {
                alert("正在处理，请不要重复点击");
                return false;
            }

            var nickname = $(".user_edit_wrap input[name=nickname]").val();
            var email = $(".user_edit_wrap input[name=email]").val();
            if(nickname.length < 1)
            {
                alert("请输入合法的姓名~~~");
                return false;
            }
            if(email.length < 1)
            {
                alert("请输入合法的邮箱~~~");
                return false;
            }

            btn_target.addClass("disabled");

            $.ajax({
                url:"/web/user/edit",
                data:{
                    nickname : nickname,
                    email : email,
                },
                type:"POST",
                dataType:"json",
                success:function (res) {
                    btn_target.removeClass("disabled");
                    alert(res.msg);
                }
            })
        })
    }
}

$(document).ready(function () {
    user_edit_ops.init();
})