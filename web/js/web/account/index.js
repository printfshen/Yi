;
var account_index_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        //搜索
        var that = this;
        $(".search").click(function () {
            $(".wrap_search").submit();
        });

        //删除
        $('.remove').click(function () {
            // if (!common_ops.confirm("请确认删除？"))
            // {
            //     return;
            // }
            that.ops("remove", $(this).attr("data"));
        });

        //恢复账号删除
        $(".recover").click(function () {
            // if (!common_ops.confirm("请确认恢复？"))
            // {
            //     return;
            // }
            that.ops("recover", $(this).attr("data"));
        });

    },
    ops:function (act, uid) {
        callback = {
            "ok":function () {
                $.ajax({
                    url:common_ops.buildWebUrl("/account/ops"),
                    type:"POST",
                    data:{
                        act : act,
                        uid : uid,
                    },
                    dataType : "json",
                    success:function (res) {
                        callback = null;
                        if(res.code == 200)
                        {
                            callback = function () {
                                window.location.href = window.location.href;
                            }
                        }
                        common_ops.alert(res.msg, callback);
                    }
                });
            },
            "cancel":function () {
                
            }
        }
        common_ops.confirm((act == "remove") ? "你确认删除？" : "你确认恢复？", callback);
    }
};
$(document).ready(function () {
    account_index_ops.init();
});