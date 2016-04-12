<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>婚礼－新人姓名</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">婚礼</h2>
        <div class="r_btn" >确定</div>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">新郎姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="新郎姓名" id="groom_name"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="手机号" id="groom_phone"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">微信</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="微信" id="groom_wechat"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">QQ</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="QQ" id="groom_qq"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">新娘姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="新娘姓名" id="bride_name"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="手机号" id="bride_phone"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">微信</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="微信" id="bride_wechat"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">QQ</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="QQ" id="bride_qq"/>
                </div>
            </li>
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //判断是否有customer_info的本地存储
        if (localStorage.getItem("customer_info") != null) {
            var customer_info = $.parseJSON(localStorage.getItem("customer_info"));
            $("#groom_name").val(customer_info.groom_name);
            $("#groom_phone").val(customer_info.groom_phone),
                $("#groom_wechat").val(customer_info.groom_wechat),
                $("#groom_qq").val(customer_info.groom_qq),
                $("#bride_name").val(customer_info.bride_name),
                $("#bride_phone").val(customer_info.bride_phone),
                $("#bride_wechat").val(customer_info.bride_wechat),
                $("#bride_qq").val(customer_info.bride_qq)
        }

        //点击返回按钮
        $(".l_btn").on("click", function () {
            if ($.util.param("from") == "index") {
                location.href = "<?php echo $this->createUrl("order/index", array());?>";
            } else if ($.util.param("from") == "my_order") {
                location.href = "<?php echo $this->createUrl("order/my", array());?>";
            }
        })

        //点确定按钮
        $(".r_btn").on("click", function () {
            if ($("#groom_name").val() == "" && $("#bride_name").val() == "") {
                alert("请输入至少一个新人姓名");
            } else if ($("#groom_phone").val() != "" && !$.regex.isPhone($("#groom_phone").val())) {
                alert("请输入正确的新郎手机号");
            } else if ($("#bride_phone").val() != "" && !$.regex.isPhone($("#bride_phone").val())) {
                alert("请输入正确的新娘手机号");
            }
            else {
                var get_info = getinfo();
                console.log(get_info);
                $.post('<?php echo $this->createUrl("plan/weddetailinsert");?>',get_info,function(retval){
                    location.href = "<?php echo $this->createUrl("order/my", array());?>&account_id=<?php echo $_SESSION['account_id']?>&code=&t=plan&department=";
                    /*alert(retval);*/
                });
                
            }
        })

        //获得页面数据
        function getinfo() {
            var mydate = new Date();
            var year = mydate.getFullYear() + "";
            var month = mydate.getMonth() + 1;
            var month = month + "";
            var date = mydate.getDate() + "";
            var hours = mydate.getHours() + "";
            var minutes = mydate.getMinutes() + "";
            var seconds = mydate.getSeconds() + "";

            var time = year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds;

            var get_info = {
                account_id: 1,
                order_id: $.util.param("order_id"),
                groom_name: $("#groom_name").val(),
                groom_phone: $("#groom_phone").val(),
                groom_wechat: $("#groom_wechat").val(),
                groom_qq: $("#groom_qq").val(),
                bride_name: $("#bride_name").val(),
                bride_phone: $("#bride_phone").val(),
                bride_wechat: $("#bride_wechat").val(),
                bride_qq: $("#bride_qq").val(),
                update_time : time,
            }
            return get_info;
        }
    })
</script>
</body>
</html>
