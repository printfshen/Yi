;
var book_index_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function () {


        //搜索
        $('.wrap_search .search').click(function () {
            $('.wrap_search').submit();
        })

    },
};
$(document).ready(function () {
    book_index_ops.init();
})