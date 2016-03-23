<?php
$arr = array(
    'customer' => $_GET['order_name'],
    'linkman' => $linkmanname,
    'tele' => $linkmanphone,
    'layout' => $layout,
    'meeting_time' => $time_type

);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>详细信息</title>
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
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">详细信息</h2>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">客户信息</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item" id="customer">
                <div class="int_bar list_more"><?php echo $arr['customer']; ?></div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">联系人</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item" id="linkman">
                <div class="int_bar list_more"><?php echo $arr['linkman']; ?><i
                        class="t_green"><?php echo $arr['tele']; ?></i></div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">台型</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item" id="layout">
                <div class="int_bar list_more"><?php echo $arr['layout']; ?></div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">时间</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item" id="meeting_time">
                <div class="int_bar list_more"><?php echo $arr['meeting_time']; ?></div>
            </li>
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        $("#customer").on("click", function () {
            location.href = "<?php echo $this->createUrl("meeting/selectCustomerInfo");?>";
        })

        $("#linkman").on("click", function () {
            location.href = "<?php echo $this->createUrl("meeting/selectLinkmanInfo");?>";
        })

        $("#layout").on("click", function () {
            location.href = "<?php echo $this->createUrl("meeting/selectLayoutInfo");?>";
        })

        $("#meeting_time").on("click", function () {
            location.href = "<?php echo $this->createUrl("meeting/selectTimeInfo");?>";
        })
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("meeting/detail");?>";
        })
    })
</script>
</body>
</html>
