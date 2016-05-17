!(function ($, window, undefined) {
    var Count = function (ele, options) {
        this.settings = $.extend({}, Count.defaults, options);
        this.$ele = $(ele);
        this.init();
    };
    Count.defaults = {
        limitnum: 'null'
    }
    Count.prototype = {
        constructor: Count,
        init: function () {
            this.$ele.find('.minus_btn').on('click', $.proxy(this.minus, this));
            this.$ele.find('.add_btn').on('click', $.proxy(this.add, this));
        },
        minus: function () {
            var $count = this.$ele.find('.count'),
                $add_btn = this.$ele.find('.add_btn'),
                $minus_btn = this.$ele.find('.minus_btn'),
                num = $count.val();
            if (Number(num) > 1) {
                $count.val(Number(num) - 1);
                $add_btn.removeClass('disabled');
                if (Number(num) - 1 == 1) {
                    $minus_btn.addClass('disabled');
                }
            }
        },
        add: function () {
            var $count = this.$ele.find('.count'),
                $add_btn = this.$ele.find('.add_btn'),
                $minus_btn = this.$ele.find('.minus_btn'),
                num = $count.val(),
                limitnum = this.settings.limitnum;
            if (Number(num) < limitnum || limitnum == 'null') {
                $count.val(Number($count.val()) + 1);
                $minus_btn.removeClass('disabled');
                if (Number(num) == limitnum - 1) {
                    $add_btn.addClass('disabled');
                }
            }

        }
    }

    $.fn.count = function (options) {
        return this.each(function () {
            return new Count(this, options);
        })
    }
})(jQuery, window)
$(function () {
    /*导航显隐控制*/
    $(".nav_list li:first").mouseover(function () {
            $(".sub_nav_list").show();
        }).mouseout(function () {
            $(".sub_nav_list").hide();
        })
        /*数量增减控制*/
    $(".counter_box").count({
            limitnum: 5
        })
        /*左侧固定*/
    var $right_fixed = $(".upload_set_c .right_area"),
        $left_fixed = $(".upload_set_c .left_area")
    var wapper = 960,
        w_width = document.documentElement.clientWidth,
        w_height = document.documentElement.clientHeight;
    console.log((w_width - wapper) / 2);
    var h_footer_fixed = $(".footer").outerHeight();
    $right_fixed.css({
        'right': (w_width - wapper) / 2 + 'px',
        'height': w_height - h_footer_fixed - 140 + 'px'
    });
    $left_fixed.css({
        'height': w_height - h_footer_fixed - 140 + 'px'
    });
    $(".upload_set_c .right_area>div:first").css({
        'height': w_height - h_footer_fixed - 190 + 'px'
    });

})