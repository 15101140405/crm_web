<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>宴会</title>
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
        <h2 class="page_title">宴会</h2>
        <div class="r_btn">重选</div>
    </div>
    <div class="order_abstract mar_b10" id="total">
        <?php
        $arr_wed_feast = array(//background data
            'name' => '喜庆临门1',
            'unit_cost' => '63533',
            'discount' => '283',
            'table_num' => '23',
            'service_charge_ratio' => '10%'
        );
        ?>
        <p class="title"><?php echo $arr_wed_feast['name']; ?> &yen;<?php echo $arr_wed_feast['unit_cost']; ?></p>
        <p class="desc">
            <span>折扣价:&yen;<?php echo $arr_wed_feast['discount']; ?></span>
            <span><?php echo $arr_wed_feast['table_num']; ?>桌</span>
            <span><?php echo $arr_wed_feast['service_charge_ratio']; ?>服务费</span>
        </p>
    </div>
    <div class="ulist_module">
        <ul class="ulist menu_list">
            <?php
            $arr_wed_list = array(//background data
                '0' => array(
                    'id' => '1',
                    'name' => '喜庆临门12',
                    'unit_cost' => '633',
                    'service_charge_ratio' => '10%'
                ),
                '1' => array(
                    'id' => '2',
                    'name' => '喜庆临门1',
                    'unit_cost' => '63523',
                    'service_charge_ratio' => '10%'
                )

            );

            foreach ($arr_wed_list as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $list[$key1] = $value1;
                }
                ?>
                <!-- 选中的时候加class -->
                <li class="ulist_item selected" product-id="<?php echo $list['id']; ?>">
                    <div class="dishes">
                        <p class="name"><?php echo $list['name']; ?></p>
                    </div>
                    <div>
                        <p class="price">&yen;<?php echo $list['unit_cost']; ?>/桌</p>
                        <p class="fee"><?php echo $list['service_charge_ratio']; ?>服务费</p>
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

        //判断宴会是否已选，若已选，调取数据并显示出来
        <?php
        $selected_id = 1;//background data
        ?>

        var product_id = "<?php echo $selected_id;?>"; //feast_name用php赋值

        if (product_id == "") {
            $(".selected").addClass("list_more");
            $(".selected").removeClass("selected");
            $(".r_btn").remove();
            $("#total").remove();
        } else {
            $(".list_more").removeClass("list_more");
            $(".selected").removeClass("selected");
            $("[product-id='" + product_id + "']").addClass("selected");

        }

        //返回按钮
        $(".l_btn").on("click", function () {
            if ($.util.param("type") == "meeting") {
                location.href = "<?php echo $this->createUrl("meeting/detail", array());?>&from=" + $.util.param("from");
            }
            else {
                location.href = "<?php echo $this->createUrl("plan/detail", array());?>&from=" + $.util.param("from");
            }

        })

        //重选按钮
        $(".r_btn").on("click", function () {
            $(".selected").addClass("list_more");
            $(".selected").siblings().addClass("list_more");
            $(".selected").removeClass("selected");
            $(".r_btn").remove();
            $("#total").remove();
        })

        //空订单，选择宴会
        $(".list_more").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/selectFeast", array());?>&from=" + $.util.param("from") + "&type=" + $.util.param("type") + "&state＝new";
        })

        //修改订单
        $("li.selected").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/selectFeast", array());?>&from=" + $.util.param("from") + "&type=" + $.util.param("type") + "&state＝edit";
        })

    })
</script>
</body>
</html>