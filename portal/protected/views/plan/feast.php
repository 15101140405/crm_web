<?php 
    $light_bill=array(
            'total' => 10000,
        )
?>
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
        <h2 class="page_title">婚宴</h2>
    </div>
    <div class="order_abstract mar_b10">
        <p class="title">总价 &yen;<?php echo $light_bill['total']; ?></p>
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
        //渲染页面
        var html_feast = '';
                <?php
                $arr_feast = array(//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => '婚宴套餐A',
                        'supplier_name' => '小李3'
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '婚宴套餐B',
                        'supplier_name' => '小李'
                    )

                );
                foreach ($arr_feast as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $feast[$key1] = $value1;
                }
                ?>
                    html_feast += '<li class="ulist_item list_more " product-id="screen<?php echo $feast['product_id'];?> ">';
                    html_feast += '<div class="item ">';
                    html_feast += '<p class="name"><?php echo $feast['name'];?></p>';
                    html_feast += '</div><i class="name"><?php echo $feast['supplier_name'];?></p></i>';
                    html_feast += '</li>';
                <?php
                }
                ?>
                $("#product").empty(); //清空订单列表
                $("#product").prepend(html_feast); //打印新的订单列表

        //点击返回按钮，判断from，返回对应页面
        $(".l_btn").on("click", function () {
            if ($.util.param("from") == "detail") {
                location.href = "<?php echo $this->createUrl("design/detail");?>";
            } else {
                location.href = "<?php echo $this->createUrl("design/bill", array());?>";
            }
        });

        //渲染对应内容
        /*function showdata(data) {
            var type_id = data;
            switch (type_id) {
                case "lighting":
                    var html_lighting = '';
                <?php
                $arr_category_light = array(//background data
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

                );
                foreach ($arr_category_light as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_light[$key1] = $value1;
                }
                ?>

                    html_lighting += '<li class="ulist_item list_more " product-id="lighting<?php echo $arr_light['product_id'];?>">';//id由php从后端取数，格式为lighting＋序号；
                    html_lighting += '<div class="item">';
                    html_lighting += '<p class="name"><?php echo $arr_light['name'];?></p>';
                    html_lighting += '</div><i class="name"><?php echo $arr_light['supplier_name'];?></i>';
                    html_lighting += '</li>';
                <?php } ?>
                    $("#product").empty(); //清空订单列表
                    $("#product").prepend(html_lighting); //打印新的订单列表

                    //先判断是否已经选择主持人
                    var product_id = "";   //php从后端取得“已经选择的主持人，的id”，此处暂时做个示例
                    if (product_id != "") {
                        $("#product .ulist_item").removeClass("list_more");
                        $("[product-id='" + product_id + "']").addClass("selected");

                    }
                    ;

                    //点击li跳转子页(渲染后界面)
                    $("#product li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=edit&tab=lighting&from=" + $.util.param("from");
                    })
                    $("#product li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=new&tab=lighting&from=" + $.util.param("from");
                    })
                    break;

                case "screen":
                    

                    //先判断是否已经选择摄像
                    var product_id = "screen1";   //php从后端取得此数，此处暂时做个示例
                    if (product_id != "") {
                        $("[product-id='" + product_id + "']").removeClass("list_more");
                        $("[product-id='" + product_id + "']").addClass("selected");
                    }
                    ;

                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=edit&tab=screen&from=" + $.util.param("from");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("product-id") + "&type=new&tab=screen&from=" + $.util.param("from");
                    })
                    break;
            }
        }*/


    })
</script>
</body>
</html>