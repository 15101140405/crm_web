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
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">产品列表</h2>
        <div class="r_btn" data-icon="&#xe767;"></div>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <?php
            //background data
            // $arr = array(

            //     '0' => array('product_id' => '001',
            //         'name' => '产品名称1'
            //     ),
            //     '1' => array('product_id' => '003',
            //         'name' => '产品名称2'
            //     )
            // );
            foreach ($arr as $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_list[$key1] = $value1;
                }
                ?>
                <li class="int_ulist_item list_more" id="supplier_type"
                    product-id="<?php echo $arr_list['product_id']; ?>">
                    <span><?php echo $arr_list['name']; ?></span>
                    <div class="align_r supplier_type"></div>
                </li>
            <?php } ?>
        </ul>
    </div>
</article>
<script>
    $(function () {
        //获取supplier_id
        var supplier_id = escape(unescape($.util.param("supplier_id")));

        //右上角增加按钮，进入add_product_detail
        $(".tool_bar .r_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("product/add");?>&supplier_id=" + supplier_id ;
        });

        //点击某个供应商，进入add_product_detail进行信息修改
        $(".int_ulist li").on("click", function () {
            var product_id = escape($(this).attr("product-id"));
            location.href = "<?php echo $this->createUrl("product/add");?>&product_id=" + product_id + "&supplier_id=" + supplier_id;
        });

        //点击返回按钮,回到 供应商列表
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("supplier/list");?>";
        });

    })
</script>
</body>
</html>