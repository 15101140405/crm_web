<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>婚纱&婚品</title>
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
        <h2 class="page_title">婚纱&婚品</h2>
    </div>
    <!-- 所有的子项目报价单都是此模板 -->
    <div class="order_abstract mar_b10">
        <?php
        /*$dress_bill = array(
            'total' => '2354',
            'desc' => array(
                '平面设计1' => '1033',
                '视频设计' => '10022'
            )

        );*/
        ?>
        <p class="title">总价 &yen;<?php echo $dress_bill['total']; ?></p>
        <div class="desc">
            <?php
            foreach ($dress_bill['desc'] as $key => $value) {
                ?>
                <p style="width:50%;"><?php echo $key; ?> &yen;<?php echo $value; ?></p>
            <?php } ?>
        </div>
    </div>
    <!-- 为了全站ui统一, 这里的tab样式我给换了 -->
    <div class="tab_module">
        <p class="tab_btn act" type-id="dress">
            <span>婚纱礼服</span>
        </p>
        <p class="tab_btn" type-id="appliance">
            <span>婚品</span>
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
            showdata("dress");
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
            if ($.util.param("from") == "detail") {
                location.href = "<?php echo $this->createUrl("design/detail");?>";
            } else {
                location.href = "<?php echo $this->createUrl("design/bill", array());?>";
            }
        });

        //渲染对应内容
        function showdata(data) {
            var type_id = data;
            switch (type_id) {
                case "dress":
                    var html_dress = '';
                <?php
/*                $arr_category_dress = array(
                    '0' => array(
                        'product_id' => '2',
                        'name' => '进口婚纱2'
                        //!$ 此处取出供应商姓名
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '进口礼服'
                        //!$ 此处取出供应商姓名
                    )

                );*/
                foreach ($arr_category_dress as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_dress[$key1] = $value1;
                }
                ?>
                    html_dress += '<li class="ulist_item list_more " dress-id="<?php echo $arr_dress['product_id'];?>">';//id由php从后端取数，格式为lighting＋序号；
                    html_dress += '<div class="item">';
                    html_dress += '<p class="name"><?php echo $arr_dress['name'];?></p>';
                    html_dress += '</div><i class="name"><?php echo $arr_dress['supplier_name'];?></i>';   //!$ 此处加入了供应商姓名
                    html_dress += '</li>';
                <?php
                }
                ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_dress); //打印新的订单列表

                    //先判断是否已经选择平面设计
                <?php
                foreach ($dress_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var dress_id = "<?php echo $data; ?>";
                    if (dress_id != "") {
                        $("[dress-id='" + dress_id + "']").removeClass("list_more");
                        $("[dress-id='" + dress_id + "']").addClass("selected");

                    }
                    ;
                <?php
                }
                ?>

                    //点击li跳转子页(渲染后界面)
                    $("#product li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("dress-id") + "&type=edit&tab=dress&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("#product li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("dress-id") + "&type=new&tab=dress&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "appliance":
                    var html_appliance = '';
                <?php
                /*$arr_category_appliance = array(
                    '0' => array(
                        'product_id' => '2',
                        'name' => '喜糖2'
                        //!$ 此处取出供应商姓名
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '喜糖盒1'
                        //!$ 此处取出供应商姓名
                    )

                );*/
                foreach ($arr_category_appliance as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_appliance[$key1] = $value1;
                }
                ?>
                    html_appliance += '<li class="ulist_item list_more " appliance-id="<?php echo $arr_appliance['product_id'];?>">';
                    html_appliance += '<div class="item ">';
                    html_appliance += '<p class="name"><?php echo $arr_appliance['name'];?></p>';
                    html_appliance += '</div><i class="name"><?php echo $arr_appliance['supplier_name'];?></i>';   //!$ 此处加入了供应商姓名
                    html_appliance += '</li>';
                <?php
                }
                ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_appliance); //打印新的订单列表

                    //先判断是否已经选择摄像
                <?php
                foreach ($appliance_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var appliance_id =  "<?php echo $data; ?>";   //php从后端取得此数，此处暂时做个示例
                    if (appliance_id != "") {
                        $("[appliance-id='" + appliance_id + "']").removeClass("list_more");
                        $("[appliance-id='" + appliance_id + "']").addClass("selected");
                    }
                    ;
                <?php
                }
                ?>
                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("appliance-id") + "&type=edit&tab=appliance&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("appliance-id") + "&type=new&tab=appliance&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;
            }
        }


    })
</script>
</body>
</html>