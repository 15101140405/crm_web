$(function () {
    /*导航切换*/
    $("#main_nav li").on('click', function () {
        $(this).addClass('active').siblings().removeClass('active');
    })
})