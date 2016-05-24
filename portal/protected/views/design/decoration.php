<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>场地布置</title>
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
        <h2 class="page_title">场地布置</h2>
    </div>
    <div class="order_abstract mar_b10">
        <p class="title" style="width:70%;display:inline-block !important;">总价 &yen;<?php echo $product_total; ?></p>
        <div class="singup" style="text-align:center;" id="add">添加商品</div>
    </div>
    <div class="tab_module">
        <p class="tab_btn act" type-id="standard">
            <span>标准产品</span>
        </p>
        <p class="tab_btn" type-id="nostandard">
            <span>非标产品</span>
        </p>
    </div>
    <div class="ulist_module">
        <ul class="ulist charge_list" id="product">
        </ul>
    </div>
</article>   
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //从item返回，渲染对应item页面
        if ($.util.param("tab") != "") {
            showdata($.util.param("tab"));
            $("[type-id='" + $.util.param("tab") + "']").addClass("act");
            $("[type-id='" + $.util.param("tab") + "']").siblings().removeClass("act");
        } else {
            showdata("standard");
        }

        //点击导航条，渲染对应内容
        $(".tab_btn").on("click", function () {
            //改变按钮状态
            $(this).addClass("act");
            $(this).siblings().removeClass("act");
            showdata($(this).attr("type-id"));
        })



        //返回
        $(".l_btn").on("click", function () {
            if("<?php echo $_GET['from']?>" == "design"){
                location.href = "<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET['order_id']?>";
            }else if("<?php echo $_GET['from']?>" == "meeting"){
                location.href = "<?php echo $this->createUrl("meeting/bill");?>&order_id=<?php echo $_GET['order_id']?>";
            }
                
        });

        //渲染对应内容
        function showdata(data) {
            var type_id = data;
            switch (type_id) {
                case "standard":
                    var html_standard = '';
                    <?php
                    foreach ($product_list as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $product[$key1] = $value1;
                        }
                        if ($product['standard_type'] == 0) {
                    ?>
                        html_standard += '<li class="ulist_item list_more" product-id="<?php echo $product['id']; ?>"><div class="item"><p class="name"><?php echo $product['product_name'];?></p></div><i class="name"><?php echo $product['staff_name'];?></p></i></li>';
                    <?php }} ?>
    
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_standard); //打印新的订单列表
            
                    //已选产品
                    <?php
                    foreach ($data as $key => $value) {
                        $data_id=$value['product_id'];
                    ?>
                        var product_id = "<?php echo $data_id; ?>";    //php从后端取得“已经选择的产品的id”，此处暂时做个示例
                        if (product_id != "") {
                            $("[product-id='" + product_id + "']").removeClass("list_more");
                            $("[product-id='" + product_id + "']").addClass("selected");
                        };
                    <?php } ?>

                    //点击li跳转子页(渲染后界面)
                    $("#product li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=edit&tab=standard&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("#product li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=new&tab=standard&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "nostandard":
                    var html_nostandard = '';
                    <?php
                    foreach ($product_list as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $product[$key1] = $value1;
                        };
                        if ($product['standard_type'] == 1) {

                    ?>
                        html_nostandard += '<li class="ulist_item selected linshi" linshi-id="<?php echo $product['id']; ?>"><div class="item"><p class="name"><?php echo $product['product_name'];?></p></div><i class="name"><?php echo $product['staff_name'];?></p></i></li>';
                    <?php }} ?>

                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_nostandard); //打印新的订单列表 

                    //已选
                    $(".linshi").addClass('hid');
                    <?php
                    foreach ($data as $key => $value) {
                        $data_id=$value['product_id'];
                    ?>
                        var product_id = "<?php echo $data_id; ?>";   //php从后端取得此数，此处暂时做个示例
                        $("[linshi-id='" + product_id + "']").removeClass("hid");
                
                    <?php
                    }
                    ?>

                    break;
            }};
        //点击返回按钮，判断from，返回对应页面
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/bill", array());?>&order_id=" + $.util.param("order_id");
        });

        //添加商品
        $("#add").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/decorationDetail", array());?>&type=new&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
        });

        //编辑商品
        $(".linshi").live("click", function () {
            location.href = "<?php echo $this->createUrl("design/decorationDetail", array());?>&type=edit&product_id=" + $(this).attr("linshi-id") + "&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id")
        });




    })
</script>
</body>
</html>
