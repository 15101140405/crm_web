<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>平面&视频设计</title>
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
        <h2 class="page_title">平面&视频设计</h2>
    </div>
    <?php
    /*$film_bill = array(
        'total' => '2354',
        'desc' => array(
            '平面设计1' => '1033',
            '视频设计' => '10022'
        )

    );*/
    ?>

    <!-- 所有的子项目报价单都是此模板 -->
    <div class="order_abstract mar_b10">
        <p class="title">总价 &yen;<?php echo $film_bill['total']; ?></p>
        <div class="desc">
            <?php
            foreach ($film_bill['desc'] as $key => $value) {
                ?>
                <p style="width:50%;"><?php echo $key; ?> &yen;<?php echo $value; ?></p>
            <?php } ?>
        </div>
    </div>
    <!-- 为了全站ui统一, 这里的tab样式我给换了 -->
    <div class="tab_module">
        <p class="tab_btn act" type-id="graphic">
            <span>平面设计</span>
        </p>
        <p class="tab_btn" type-id="film">
            <span>视频设计</span>
        </p>
    </div>
    <div class="ulist_module">
        <ul class="ulist charge_list" id="product">
            <!-- 选中的时候加class -->
            <!-- <li class="ulist_item selected">
                <div class="item">
                    <p class="name">套餐A</p>
                </div>
                <div>
                    <p class="price">&yen;2888/场</p>
                </div>
            </li>
            <li class="ulist_item list_more">
                <div class="item">
                    <p class="name">套餐B</p>
                </div>
                <div>
                    <p class="price">&yen;2888/场</p>
                </div>
            </li> -->
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
            showdata("graphic");
        }

        //点击导航条，渲染对应内容
        $(".tab_btn").on("click", function () {
            //改变按钮状态
            $(this).addClass("act");
            $(this).siblings().removeClass("act");
            showdata($(this).attr("type-id"));
        })

        //点击返回按钮，判断from，返回对应页面
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET['order_id']?>";
        });

        //渲染对应内容
        function showdata(data) {
            var type_id = data;
            switch (type_id) {
                case "graphic":
                    var html_graphic = '';
                <?php
/*                $arr_category_graphic = array(
                    '0' => array(
                        'product_id' => '2',
                        'name' => '桌卡2'
                        //!$ 此处取出供应商姓名
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '背景喷绘s'
                        //!$ 此处取出供应商姓名
                    )

                );*/
                foreach ($arr_category_graphic as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_graphic[$key1] = $value1;
                }
                ?>
                    html_graphic += '<li class="ulist_item list_more " graphic-id="<?php echo $arr_graphic['product_id'];?>">';//id由php从后端取数，格式为lighting＋序号；
                    html_graphic += '<div class="item">';
                    html_graphic += '<p class="name"><?php echo $arr_graphic['name'];?></p>';
                    html_graphic += '</div><i class="name"><?php echo $arr_graphic['supplier_name'];?></i>';   //!$ 此处加入了供应商姓名
                    html_graphic += '</li>';
                <?php
                }

                ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_graphic); //打印新的订单列表

                    //先判断是否已经选择平面设计
                <?php
                foreach ($graphic_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var graphic_id = "<?php echo $data; ?>"; 
                    if (graphic_id != "") {
                        $("[graphic-id='" + graphic_id + "']").removeClass("list_more");
                        $("[graphic-id='" + graphic_id + "']").addClass("selected");

                    }
                    ;
                <?php
                }
                ?>
                    //点击li跳转子页(渲染后界面)
                    $("#product li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("graphic-id") + "&type=edit&tab=graphic&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("#product li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("graphic-id") + "&type=new&tab=graphic&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "film":
                    var html_film = '';
                <?php
                /*$arr_category_film = array(
                    '0' => array(
                        'product_id' => '1',
                        'name' => '1微电影'
                        //!$ 此处取出供应商姓名
                    ),
                    '1' => array(
                        'product_id' => '2',
                        'name' => '电子相册2'
                        //!$ 此处取出供应商姓名
                    )

                );*/
                foreach ($arr_category_film as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_film[$key1] = $value1;
                }
                ?>
                    html_film += '<li class="ulist_item list_more " film-id="<?php echo $arr_film['product_id'];?>">';
                    html_film += '<div class="item ">';
                    html_film += '<p class="name"><?php echo $arr_film['name'];?></p>';
                    html_film += '</div><i class="name"><?php echo $arr_film['supplier_name'];?></i>';   //!$ 此处加入了供应商姓名
                    html_film += '</li>';

                <?php

                }
                ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_film); //打印新的订单列表

                    //先判断是否已经选择摄像
                <?php
                foreach ($film_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var film_id = "<?php echo $data; ?>";   //php从后端取得此数，此处暂时做个示例
                    if (film_id != "") {
                        $("[film-id='" + film_id + "']").removeClass("list_more");
                        $("[film-id='" + film_id + "']").addClass("selected");
                    }
                    ;
                <?php
                }
                ?>
                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("film-id") + "&type=edit&tab=film&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("film-id") + "&type=new&tab=film&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;
            }
        }


    })
</script>
</body>
</html>
