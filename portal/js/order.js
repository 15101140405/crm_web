$(function () {
    var $mask=$('.filter_area .mask'),
        $filter_list=$(".filter_class");
    /*点击下拉*/
    $(".filter_exit .all").on('click', function () {
        if ($(this).attr('data-flag') == '0') {
            $(this).find('img').addClass('up');
            $filter_list.slideDown();
            $mask.addClass('show');
            $(this).attr('data-flag', '1');
        } else {
            $(this).find('img').removeClass('up');
            $filter_list.slideUp();
            $mask.removeClass('show');
            $(this).attr('data-flag', '0');
        }

    })
    $mask.click(function () {
        $(".filter_exit .all").find('img').removeClass('up');
        $filter_list.slideUp();
        $(this).removeClass('show');
        $(".filter_exit .all").attr('data-flag', '0');
    });

    /*单选按钮*/
    $filter_list.on('click', 'li', function () {
        $(this).find('.radio').addClass('checked').attr('data-radio', '1').parent().siblings().find('.radio').removeClass('checked').attr('data-radio', '0');
        $filter_list.slideUp();
        $(".filter_exit .all").attr('data-flag', '0');
        $(".filter_exit .all img").removeClass('up');
        $mask.removeClass('show');
    })

    var currYear = (new Date()).getFullYear();
    var opt = {};
    opt.date = {
        preset: 'date'
    };
    opt.default = {
        theme: 'android-ics light', //皮肤样式
        display: 'modal', //显示方式 
        mode: 'scroller', //日期选择模式
        dateFormat: 'yyyy-mm-dd'
        , lang: 'zh'
        , showNow: true
        , nowText: "今天"
        , startYear: currYear - 10, //开始年份
        endYear: currYear + 10 //结束年份
    };
    $("#appDate_start,#appDate_end,#appDate").mobiscroll($.extend(opt['date'], opt['default']));
})