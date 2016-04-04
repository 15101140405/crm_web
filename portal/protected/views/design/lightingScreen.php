<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>灯光视频</title>
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
        <h2 class="page_title">灯光视频</h2>
    </div>
    <!-- 所有的子项目报价单都是此模板 -->
    <?php
    /*$light_bill = array(//background data
        'total' => '2322',
        'desc' => array(
            '灯光' => '1033',
            '视频' => '10022'
        )

    );*/
    ?>

    <div class="order_abstract mar_b10">
        <p class="title">总价 &yen;<?php echo $light_bill['total']; ?></p>
        <div class="desc">
            <?php
            foreach ($light_bill['desc'] as $key => $value) {
                ?>
                <p style="width:50%;"><?php echo $key; ?> &yen;<?php echo $value; ?></p>
            <?php } ?>
        </div>
    </div>
    <!-- 为了全站ui统一, 这里的tab样式我给换了 -->
    <div class="tab_module">
        <p class="tab_btn act" type-id="lighting">
            <span>灯光</span>
        </p>
        <p class="tab_btn" type-id="screen">
            <span>视频</span>
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
            showdata("lighting");
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
                case "lighting":
                    var html_lighting = '';
                <?php
                /*$arr_category_light = array(//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => '套餐A',
                        'supplier_name' => '小李'
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '套餐B柯',
                        'supplier_name' => '小李'
                    )

                );*/
                foreach ($arr_category_light as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_light[$key1] = $value1;
                }
                ?>

                    html_lighting += '<li class="ulist_item list_more " lighting-id="<?php echo $arr_light['product_id'];?>">';//id由php从后端取数，格式为lighting＋序号；
                    html_lighting += '<div class="item">';
                    html_lighting += '<p class="name"><?php echo $arr_light['name'];?></p>';
                    html_lighting += '</div><i class="name"><?php echo $arr_light['supplier_name'];?></i>';
                    html_lighting += '</li>';
                <?php } ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_lighting); //打印新的订单列表

                    //先判断是否已经选择主持人
                <?php
                foreach ($lighting_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var lighting_id = "<?php echo $data; ?>";    //php从后端取得“已经选择的主持人，的id”，此处暂时做个示例
                    if (lighting_id != "") {
                        $("[lighting-id='" + lighting_id + "']").removeClass("list_more");
                        $("[lighting-id='" + lighting_id + "']").addClass("selected");

                    }
                    ;
                <?php
                }
                ?>

                    //点击li跳转子页(渲染后界面)
                    $("#product li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("lighting-id") + "&type=edit&tab=lighting&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("#product li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("lighting-id") + "&type=new&tab=lighting&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "screen":
                    var html_screen = '';
                <?php
/*                $arr_category_screen = array(//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => 'LED 大屏2',
                        'supplier_name' => '小李3'
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '投影2',
                        'supplier_name' => '小李'
                    )

                );*/
                foreach ($arr_category_screen as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_screen[$key1] = $value1;
                }
                ?>
                    html_screen += '<li class="ulist_item list_more " screen-id="<?php echo $arr_screen['product_id'];?>">';
                    html_screen += '<div class="item ">';
                    html_screen += '<p class="name"><?php echo $arr_screen['name'];?></p>';
                    html_screen += '</div><i class="name"><?php echo $arr_screen['supplier_name'];?></p></i>';
                    html_screen += '</li>';
                <?php
                }
                ?>
                    $("#product").empty(); //清空订单列表
                    $("#made").remove()
                    $("#product").prepend(html_screen); //打印新的订单列表

                    //先判断是否已经选择摄像
                <?php
                foreach ($screen_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var screen_id = "<?php echo $data; ?>";   //php从后端取得此数，此处暂时做个示例
                    if (screen_id != "") {
                        $("[screen-id='" + screen_id + "']").removeClass("list_more");
                        $("[screen-id='" + screen_id + "']").addClass("selected");
                    }
                    ;
                <?php
                }
                ?>

                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("screen-id") + "&type=edit&tab=screen&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("screen-id") + "&type=new&tab=screen&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;
            }
        }


    })
</script>
</body>
</html>