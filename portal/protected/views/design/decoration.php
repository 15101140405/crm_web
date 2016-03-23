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
    <!-- 所有的子项目报价单都是此模板 -->
    <?php
    $total = 3242; //总价
    $li_head = array(
        '0' => '例图',
        '1' => '品名',
        '2' => '单价',
        '3' => '规格',
        '4' => '数量',
        '5' => '总价'
    );
    /*$product_list = array(
        '0' => array(
            'poduct_id' => '1',
            'img' => 'meeting_layout.jpg',
            'name' => '桌花',
            'unit_price' => '2000',
            'unit' => '个',
            'amount' => '4',
            'total' => '6003'


        ),
        '1' => array(
            'poduct_id' => '2',
            'img' => 'meeting_layout.jpg',
            'name' => '桌花',
            'unit_price' => '2003',
            'unit' => '个',
            'amount' => '3',
            'total' => '6003'

        )


    );*/

    ?>
    <div class="order_abstract mar_b10">
        <p class="title" style="width:70%;display:inline-block !important;">总价 &yen;<?php echo $product_total; ?></p>
        <div class="singup" style="text-align:center;" id="add">添加商品</div>
    </div>

    <div class="ulist_module">
        <ul class="ulist aligncenter">
            <!-- 表头处理方式和列表一样即可 -->
            <!-- <li class="ulist_item flex">
                <?php
                foreach ($li_head as $key => $value) {
                    ?>
                    <span class="flex1"><?php echo $value; ?></span>
                <?php } ?>
            </li> -->
            <!-- 正文部分多 class pad_tb10 -->
            <?php
            foreach ($product_list as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $product[$key1] = $value1;
                    
                }
                ?>
                <li class="ulist_item pad_tb10 flex" poduct-id="<?php echo $product['product_id']; ?>">
                    <div class="flex1">
                        <img src="images/<?php echo $product['img']; ?>"/>
                    </div>
                    <div class="flex1">
                        <span class="flex1" style="white-space:nowrap;overflow:hidden;display:block;"><?php echo $product['name']; ?></span>
                        <span class="flex1">&yen;<?php echo $product['unit_price']; ?></span>
                        <span class="flex1"><?php echo $product['unit']; ?></span>
                    </div>
                    <div class="flex1">
                        <span class="flex1 t_green">总价 ：<?php echo $product['total']; ?></span></br>
                        <span class="flex1 t_green">数量 ：<?php echo $product['amount'] ?></span>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //点击返回按钮，判断from，返回对应页面
        $(".l_btn").on("click", function () {
            if ($.util.param("from") == "detail") {
                location.href = "<?php echo $this->createUrl("design/detail", array());?>&order_id=" + $.util.param("order_id");
            } else {
                location.href = "<?php echo $this->createUrl("design/bill", array());?>&order_id=" + $.util.param("order_id");
            }
        });

        //添加商品
        $("#add").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/decorationDetail", array());?>&type=new&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
        })

        //编辑商品
        $("li.pad_tb10").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/decorationDetail", array());?>&type=edit&product_id=" + $(this).attr("poduct-id") + "&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id")
        })


    })
</script>
</body>
</html>
