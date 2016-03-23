$(document).ready(function () {
    $('#loginButton').click(function () {

        var url = $(this).attr('data-url');
        var username = $('#username').val();

        if (username == '') {
            $.scojs_message("用户名不能为空", $.scojs_message.TYPE_ERROR);
            return;
        }

        var password = $('#password').val();
        if (password == '') {
            $.scojs_message("密码不能为空", $.scojs_message.TYPE_ERROR);
            return;
        }

        var response = $.ajax({
            url: url,
            type: 'POST',
            data: {username: username, password: password},
            error: function () {
                $.scojs_message('通信故障', $.scojs_message.TYPE_ERROR);
            },
            success: function () {

                var data = $.parseJSON(response.responseText);
                if (data.code == 0) {
                    $.scojs_message(data.message, $.scojs_message.TYPE_OK);
                    window.setTimeout(function () {
                        window.location = data.url;
                    }, 3000);
                    return;
                }
                else if (data.code == 100) {
                    $.scojs_message(data.message, $.scojs_message.TYPE_ERROR);
                }
                else {
                    $.scojs_message('通信故障', $.scojs_message.TYPE_ERROR);
                }

            }
        });
    });

    $(window).keydown(function (event) {
        if (event.keyCode == 13) {
            $('#loginButton').click();
        }
    });
});