;
var member_index_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        var that = this;
        $(".search").click(function () {
            $(".wrap_search").submit();
        });

        $(".remove").click(function () {
            that.ops("remove", $(this).attr("data"));
        });

        $(".recover").click(function () {
            that.ops("recover", $(this).attr("data"))
        });
    },
    ops:function (act, id) {
        var callback = {
            "ok":function () {
                $.ajax({
                    url : common_ops.buildWebUrl("/member/ops"),
                    type : "post",
                    data : {
                        act : act,
                        id : id,
                    },
                    dataType : "json",
                    success:function (res) {
                        var callback = null;
                        if(res.code == 200){
                            callback = function () {
                                window.location.href = window.location.href;
                            }
                        }
                        common_ops.alert(res.msg, callback);
                    }
                })
            }
        }
        common_ops.confirm((act == "remove") ? "你确认删除？" : "你确认恢复？", callback);
    }
};
$(document).ready(function () {
    member_index_ops.init();
})