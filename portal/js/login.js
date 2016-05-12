$(function () {
    window.onload = window.onresize = function () {
        var w_width = document.documentElement.clientWidth;
        var w_height = document.documentElement.clientHeight;
        $(".login_container").css({
            'height': w_height
        })
        $(".login_con").css({
            'top': (w_height - 355) / 2 + 'px',
            'left': (w_width - 450) / 2 + 'px'
        })
    }
})