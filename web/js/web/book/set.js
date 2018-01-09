;
var upload = {
    error: function (msg) {
        $.alert(msg);
    },
    success: function (file_key, type) {
        if (!file_key) {
            return;
        }
        var html = '<img src="' + common_ops.buildPicUrl("book", file_key) + '"/>'
            + '<span class="fa fa-times-circle del del_image" data="' + file_key + '"></span>';

        if ($(".upload_pic_wrap .pic-each").size() > 0) {
            $(".upload_pic_wrap .pic-each").html(html);
        } else {
            $(".upload_pic_wrap").append('<span class="pic-each">' + html + '</span>');
        }
        book_set_ops.delete_img();
    }
};

var book_set_ops = {
    init: function () {
        this.ue = null;
        this.eventBind();
        this.initEditor();
    },
    eventBind: function () {
        var that = this;

        //图片上传
        $(".wrap_book_set .upload_pic_wrap input[name=pic]").change(function () {
            $(".wrap_book_set .upload_pic_wrap").submit();
        });

        //select2
        $(".wrap_book_set select[name=cat_id]").select2({
            language: "zh-CN",
            width: '100%'
        });

    },
    initEditor: function () {
        var that = this;
        that.ue = UE.getEditor('editor', {
            toolbars: [
                ['undo', 'redo', '|',
                    'bold', 'italic', 'underline', 'strikethrough', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', '|', 'rowspacingtop', 'rowspacingbottom', 'lineheight'],
                ['customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                    'directionalityltr', 'directionalityrtl', 'indent', '|',
                    'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                    'link', 'unlink'],
                ['imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                    'horizontal', 'spechars', '|', 'inserttable', 'deletetable', 'insertparagraphbeforetable', 'insertrow', 'deleterow', 'insertcol', 'deletecol', 'mergecells', 'mergeright', 'mergedown', 'splittocells', 'splittorows', 'splittocols']

            ],
            enableAutoSave: true,
            saveInterval: 60000,
            elementPathEnabled: false,
            zIndex: 4
        });
        that.ue.addListener('beforeInsertImage', function (t, arg) {
            console.log(t, arg);
            //alert('这是图片地址：'+arg[0].src);
            // that.ue.execCommand('insertimage', {
            //     src: arg[0].src,
            //     _src: arg[0].src,
            //     width: '250'
            // });
            return false;
        });
    },
};
$(document).ready(function () {
    book_set_ops.init();
})