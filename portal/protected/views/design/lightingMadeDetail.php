<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <!-- 所有的子项目报价单订单填写都是此模板 -->

    <!-- 成本项增加也是此模板 -->

    <title>订单填写</title>
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
        <h2 class="page_title">订单填写</h2>
    </div>
    <div class="ulist_module pad_b40">
        <ul class="ulist charge_list">
            <li class="ulist_item flex list_more" product-id="lighting000">par灯</li>
            <li class="ulist_item flex list_more" product-id="lighting001">追光灯</li>
            <li class="ulist_item flex list_more" product-id="lighting002">摇头灯</li>
            <li class="ulist_item  selected" product-id="lighting003">成像灯 <i class="left">3 盏</i></li>
            <li class="ulist_item flex list_more" product-id="lighting004">光束灯</li>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar">
        <span class="total">总计：&yen;100000</span>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //页面初始化
        if ($.util.param("type") == "edit") {

        }

        //返回按钮
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/lightingScreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from");
        });

        //跳转订单页
        $(".list_more").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=new&tab=made&from=" + $.util.param("from");
        })
        $(".selected").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=edit&tab=made&from=" + $.util.param("from");
        })
    })
</script>
</body>
</html>
