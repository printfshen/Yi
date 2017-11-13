;
var index_ops = {
    init:function () {
        this.eventBind();
    },
    eventBind:function(){
        TouchSlide({
            slideCell : "slideBox",
            titCell : ".hd ul",
            mainCell : ".bd ul",
            effect : "leftLoop",
            autoPage : true,
            autoPlay : true
        });
    }
}

$(document).ready(function () {
    index_ops.init();
})