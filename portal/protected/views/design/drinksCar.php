<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>酒水&车辆</title>
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
        <h2 class="page_title">酒水&车辆</h2>
    </div>
    <!-- 所有的子项目报价单都是此模板 -->
    <div class="order_abstract mar_b10">
        <?php
        /*$drinks_bill = array(
            'total' => '2354',
            'desc' => array(
                '酒水1' => '1033',
                '车辆' => '10022'
            )

        );*/
        ?>
        <p class="title">总价 &yen;<?php echo $drinks_bill['total']; ?></p>
        <div class="desc">
            <?php
            foreach ($drinks_bill['desc'] as $key => $value) {
                ?>
                <p style="width:50%;"><?php echo $key; ?> &yen;<?php echo $value; ?></p>
            <?php } ?>
        </div>
    </div>
    <!-- 为了全站ui统一, 这里的tab样式我给换了 -->
    <div class="tab_module">
        <p class="tab_btn act" type-id="drinks">
            <span>酒水</span>
        </p>
        <p class="tab_btn" type-id="car">
            <span>车辆</span>
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
            showdata("drinks");
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
                case "drinks":
                    var html_drinks = '';
                <?php
                /*$arr_category_drinks = array(
                    '0' => array(
                        'product_id' => '2',
                        'name' => '茅台2'
                        //!$ 此处取出供应商姓名
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '五粮液'
                        //!$ 此处取出供应商姓名
                    )

                );*/
                foreach ($arr_category_drinks as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_drinks[$key1] = $value1;
                }
                ?>
                    html_drinks += '<li class="ulist_item list_more " "[drinks-id='" + drinks_id + "']"-id="<?php echo $arr_drinks['product_id'];?>">';//id由php从后端取数，格式为lighting＋序号；
                    html_drinks += '<div class="item">';
                    html_drinks += '<p class="name"><?php echo $arr_drinks['name'];?></p>';
                    html_drinks += '</div><i class="name"><?php echo $arr_drinks['supplier_name'];?></i>';   //!$ 此处加入了供应商姓名
                    html_drinks += '</li>';
                <?php
                }
                ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_drinks); //打印新的订单列表

                    //先判断是否已经选择平面设计
                <?php
                foreach ($drinks_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var drinks_id =  "<?php echo $data; ?>"; 
                    
                    if (drinks_id != "") {
                        $("[drinks-id='" + drinks_id + "']").removeClass("list_more");
                        $("[drinks-id='" + drinks_id + "']").addClass("selected");

                    }
                    ;
                <?php
                }
                ?>
                    //点击li跳转子页(渲染后界面)
                    $("#product li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("drinks-id") + "&type=edit&tab=drinks&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("#product li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("drinks-id") + "&type=new&tab=drinks&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "car":
                    var html_car = '';
                <?php
/*                $arr_category_car = array(
                    '0' => array(
                        'product_id' => '1',
                        'name' => '路虎2'
                        //!$ 此处取出供应商姓名
                    ),
                    '1' => array(
                        'product_id' => '2',
                        'name' => '宝马'
                        //!$ 此处取出供应商姓名
                    )

                );*/
                foreach ($arr_category_car as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_car[$key1] = $value1;
                }
                ?>
                    html_car += '<li class="ulist_item list_more " car-id="<?php echo $arr_car['product_id'];?>">';
                    html_car += '<div class="item ">';
                    html_car += '<p class="name"><?php echo $arr_car['name'];?></p>';
                    html_car += '</div><i class="name"><?php echo $arr_car['supplier_name'];?></i>';   //!$ 此处加入了供应商姓名
                    html_car += '</li>';
                <?php
                }
                ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_car); //打印新的订单列表

                    //先判断是否已经选择摄像
                <?php
                foreach ($car_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var car_id =  "<?php echo $data; ?>"; 

                    if (car_id != "") {
                        $("[car-id='" + car_id + "']").removeClass("list_more");
                        $("[car-id='" + car_id + "']").addClass("selected");
                    }
                    ;
                <?php
                }
                ?>
                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("car-id") + "&type=edit&tab=car&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("car-id") + "&type=new&tab=car&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;
            }
        }


    })
</script>
</body>
</html>
