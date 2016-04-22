<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>产品列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <script src="js/zepto.min.js"></script>
    <script src="js/common.js"></script>
    <link rel="stylesheet" href="css/weui.css"/>
</head>
<body>
<article>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">产品列表</h2>
        <div class="r_btn" data-icon="&#xe767;"></div>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <?php foreach ($supplier_product as $value) { ?>
                <li class="int_ulist_item list_more" supplier-id="<?php echo $value['supplier_id']?>" product-id="<?php echo $value['id']; ?>">
                    <span><?php echo $value['product_name']; ?></span>
                    <div class="align_r supplier_type">[<?php echo $value['supplier_name']?>]</div>
                </li>
            <?php } ?>
        </ul>
    </div>
    <!-- <a href="<?php echo $this->createUrl("service/teamlist");?>&service_type=<?php echo $_GET['supplier_type']?>" class="weui_btn weui_btn_primary">更多团队</a> -->
</article>
<script>
    $(function () {

        //右上角增加按钮，进入add_product_detail
        $(".tool_bar .r_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("product/add");?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=&product_id=";
        });

        
        $(".int_ulist li").on("click", function () {
            /*var product_id = escape($(this).attr("product-id"));
            var supplier_id = escape($(this).attr("supplier-id"));*/
            location.href = "<?php echo $this->createUrl('product/selectorder');?>&product_id="+ $(this).attr("product-id") +"&category=<?php echo $_GET['category']?>";
        });

        //点击返回按钮,回到 产品库
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("supplier/list");?>";
        });

    })
</script>
</body>
</html>