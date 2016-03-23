<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单——选择类型</title>
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
        <div class="l_btn hid" data-icon="&#xe679;"></div>
        <h2 class="page_title">新增</h2>
    </div>
    <div class="select_ulist_module">
        <ul class="select_ulist">
            <li class="select_ulist_item list_more">婚礼</li>
            <li class="select_ulist_item list_more">会议</li>
        </ul>
    </div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //获得所选择的时间
        var order_date = localStorage.getItem("new_order_day");

        //点击返回按钮
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/index");?>";
            localStorage.removeItem("new_order_day");//将新建日期存储清空
        });

        //选择婚礼或者会议
        $(".select_ulist li").on("click", function () {
            var order_type = $(this).index();
            if (order_type == 0) {//婚礼
                //localStorage.setItem("order_type", "2");
                location.href = "<?php echo $this->createUrl("plan/create");?>";
            } else {//会议
                //localStorage.setItem("order_type", "1");
                location.href = "<?php echo $this->createUrl("meeting/selectTime");?>";
            }
        });

    });
</script>
</body>
</html>
