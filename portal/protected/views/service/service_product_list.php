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
        <h2 class="page_title">请选择</h2>
        <!-- <div class="r_btn" data-icon="&#xe767;"></div> -->
    </div>
    <div class="int_ulist_module">
        <ul class="charge_list">
            <?php foreach ($supplier_product as $value) { ?>
                <li class="int_ulist_item list_more" supplier-id="<?php echo $value['supplier_id']?>" product-id="<?php echo $value['id']; ?>">
                    <span><?php echo $value['name']; ?></span>
                    <div style="color:green;">[<?php echo $value['unit_price']?>]</div>
                </li>
            <?php } ?>
        </ul>
    </div>
</article>
<script>
    $(function () {
        <?php
        if (!empty($selected)) {
            foreach ($selected as $key => $value) {
        ?>
            $("[product-id='<?php echo $value['product_id']?>']").removeClass("list_more");
            $("[product-id='<?php echo $value['product_id']?>']").addClass("selected");
        <?php 
            }
        }
        ?>
        $("li.list_more").on("click", function () {
            if('<?php echo $_GET['order_id']?>' == ""){
                location.href = "<?php echo $this->createUrl('product/selectorder');?>&category=2&from=service&tab=" + $.util.param("tab") + "&product_id="+$(this).attr("product-id");    
            }else{
                location.href = "<?php echo $this->createUrl('design/tpDetail');?>&tab=<?php echo $_GET['tab']?>&from=&order_id=<?php echo $_GET['order_id']?>&type=new&product_id="+$(this).attr("product-id");
            }
            
        })
        $("li.selected").on("click", function () {
            location.href = "<?php echo $this->createUrl('design/tpDetail');?>&tab=<?php echo $_GET['tab']?>&from=&order_id=<?php echo $_GET['order_id']?>&type=edit&product_id="+$(this).attr("product-id");
        })
;
    })
</script>
</body>
</html>