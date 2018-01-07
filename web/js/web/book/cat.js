;
var book_cat_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {
        var that = this;
        //移除分类
        $(".remove").click(function () {
            that.ops("remove", $(this).attr("data"));
        });

        //恢复分类
        $(".recover").click(function () {
            that.ops('recover', $(this).attr('data'));
        });

        //切换分类状态
        $('.wrap_search select[name=status]').change(function () {
            $('.wrap_search').submit();
        })
    },

    ops:function( act,id ){
        var callback = {
            'ok':function(){
                $.ajax({
                    url:common_ops.buildWebUrl("/book/cat_ops"),
                    type:'POST',
                    data:{
                        act:act,
                        id:id
                    },
                    dataType:'json',
                    success:function( res ){
                        var callback = null;
                        if( res.code == 200 ){
                            callback = function(){
                                window.location.href = window.location.href;
                            }
                        }
                        common_ops.alert( res.msg,callback );
                    }
                });
            },
            'cancel':null
        };
        common_ops.confirm( ( act=="remove" )?"确定删除？":"确定恢复？",callback );
    }
};
$(document).ready(function () {
    book_cat_ops.init();
});