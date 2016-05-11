$(function () {
    /*单选*/
    var img_node = "<img src='images/selected.png'/>";
    $(".filter_list").on('click', '.filter_item', function () {
        if ($(this).hasClass('checkbox')) {
            if ($(this).hasClass('filter_item_all')) {
                if ($(this).attr('data-flag') == '0') {
                    $(this).append(img_node);
                }
                $(this).addClass('active').attr('data-flag', '1');
                $(this).siblings().removeClass('active').attr('data-flag', '0').find('img').remove();
            } else {
                $('.filter_item_all').removeClass('active').attr('data-flag', '0').find('img').remove();
                if ($(this).attr('data-flag') == '0') {
                    $(this).addClass('active').attr('data-flag', '1');
                    $(this).append(img_node);
                } else {
                    $(this).removeClass('active').attr('data-flag', '0');
                    $(this).find('img').remove();
                }
            }
        } else {
            if ($(this).attr('data-flag') == '0') {
                $(this).addClass('active').siblings().removeClass('active');
                $(this).append(img_node);
                $(this).attr('data-flag', '1');
                $(this).siblings().attr('data-flag', '0').find('img').remove();
            }
        }

    })
    $(".diy_price").on('click', function () {
        $(this).siblings().removeClass('active').attr('data-flag', '0').find('img').remove();
    })
    $(".clear_btn").on('click', function () {
        $(".diy_price").find('input').val('');
        $(".filter_date").val('');
        var filter_item1=$(".filter_item1");
        $(".filter_item").removeClass('active').attr('data-flag', '0').find('img').remove();
            filter_item1.attr('data-flag', '1').addClass('active').append(img_node);
    })
    /*tab切换*/
    $(".filter_condition").on('click', 'li', function () {
        var index = $(this).index();
        $(this).addClass('active').siblings().removeClass('active');
        $(".filter_content").find(".content:eq(" + index + ")").show().siblings().hide();
    })
})