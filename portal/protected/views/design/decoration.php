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
                    ?>
                    html_standard += '<li class="ulist_item list_more " poduct-id="<?php echo $product['id']; ?>"><div class="item"><p class="name"><?php echo $product['product_name'];?></p></div><i class="name"><?php echo $product['staff_name'];?></p></i></li>';
                    <?php } ?>
    
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_standard); //打印新的订单列表
            
                    //先判断是否已经选择主持人
                
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
        $("li.pad_tb10").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/decorationDetail", array());?>&type=edit&product_id=" + $(this).attr("poduct-id") + "&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id")
        });




    })
</script>
</body>
</html>
