$(function () {
   (function aslider() {
        $("[data-aslider]").on('click', function () {
            $(".aslider_scroll").addClass('show');
            $(".aslider_scroll .mask").fadeIn();
            setTimeout(function () {
                $(".aslider_scroll .wrapper").addClass('move');
            }, 10)
            $("body").addClass('stop_scroll');
            $("html").addClass("stop_scroll")
        })
        $(".aslider_scroll .mask,.close").on('click', function () {
            $(".aslider_scroll .wrapper").removeClass('move');
            $(".aslider_scroll .mask").fadeOut();
            setTimeout(function () {
                $(".aslider_scroll").removeClass('show');
            }, 300)
            $("body").removeClass('stop_scroll');
            $("html").removeClass('stop_scroll');
        })
    })()
})