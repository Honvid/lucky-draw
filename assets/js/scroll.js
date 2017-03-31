/**
 * Created by Honvid on 2017/3/31.
 */
(function($){
    $.fn.myScroll = function(options){
        var defaults = {
            speed:40,  //
            rowHeight:24, //
            margin:1 //
        };

        var opts = $.extend({}, defaults, options),intId = [];

        function marquee(obj, step){
            obj.find("ul").animate({
                marginTop: '-='+opts['margin']
            },0,function(){
                var s = Math.abs(parseInt($(this).css("margin-top")));
                if(s >= step){
                    $(this).find("li").slice(0, 1).appendTo($(this));
                    $(this).css("margin-top", 0);
                }
            });
        }

        this.each(function(i){
            var sh = opts["rowHeight"], speed = opts["speed"], _this = $(this);
            intId[i] = setInterval(function () {
                if (_this.find("ul").height() <= _this.height()) {
                    clearInterval(intId[i]);
                } else {
                    marquee(_this, sh);
                }
            }, speed);
        });
    }
})(jQuery);
