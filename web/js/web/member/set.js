;
var member_set_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        $(".save").click(function () {
            var but_target = $(this);
            if(but_target.hasClass("disabled"))
            {
                common_ops.alert("不要重复提交~~~");return;
            }

            var nickname_target = $(".wrap_member_set input[name=nickname]");
            var nickname = nickname_target.val();

            var mobile_target = $(".wrap_member_set input[name=mobile]")
            var mobile = mobile_target.val();

            var id = $(".wrap_member_set input[name=id]").val();

            if(nickname.length <= 1)
            {
                common_ops.tip("请输入符合规范的名称~~~", nickname_target);return;
            }
            if(mobile.length != 11)
            {
                common_ops.tip("请输入符合规范的电话号码~~~~", mobile_target);return;
            }

            but_target.addClass("disabled");

            var data = {
                nickname : nickname,
                mobile : mobile,
                id : id
            };

            $.ajax({
                url : common_ops.buildWebUrl("/member/set"),
                type : "post",
                data : data,
                dataType : "json",
                success:function (res) {
                    console.log(res);
                    but_target.removeClass("disabled");
                    var callback = null;
                    if( res.code == 200 ){
                        callback = function(){
                            window.location.href = common_ops.buildWebUrl("/member/index");
                        }
                    }
                    common_ops.alert( res.msg,callback );
                }
            })

        });
    },

};
$(document).ready(function () {
    member_set_ops.init();
});