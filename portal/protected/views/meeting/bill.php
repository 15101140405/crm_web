<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
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
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">报价单</h2>

    </div>
    <!-- 基本信息 -->
    <?php
    //background data
    //婚礼信息
    $meeting_info = array(
        'order_date' => '12.1.1',
        'order_name' => '北京浩瀚一方互联网科技有限责任公司1',
        'linkname' => '小斯',
        'planner' => '小明',
        'supplier' => '到喜啦',
        'meeting_discount' => '8折'

    );
    //回款信息
    $return_bill = array(
        'first' => array(
            'money' => '2000',
            'order_date' => '12 21 31'
        ),
        'second' => array(
            'money' => '2000',
            'order_date' => '12 21 31'
        ),

        'third' => array(
            'money' => '2000',
            'order_date' => '12 21 31'
        )
    );


    //总计
    $total = '120000';//小计
    $profits = 100000; //利润
    $ratio = '10%'; //毛利率
    ?>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item">
                <span class="big_font">基本信息</span>
                <!-- <a class="singup right btn_disable" id="print" href="">打印</a> -->
                <!-- <a class="singup right btn_red" id="examined" href="">审批通过</a>
                <a class="singup right btn_disable" id="examining" href="">审批中</a>
                <a class="singup right" id="non_examine" href="">核算审批</a> -->
            </li>
            <li class="ulist_item overflow1">客户名称：<span class="overflow2"><?php echo $arr_order_data['order_name']; ?><span></li>
            <!-- <li class="ulist_item">联 系 人：<?php echo $meeting_info['linkname']; ?></li> -->
            <li class="ulist_item">活动日期：<?php echo $arr_order_data['order_date']; ?></li>
            <li class="ulist_item">统 筹 师：<?php echo $planner; ?></li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more department" id="channel">
                <span class="label">推   单：</span>
                <div class="align_r1 dep_content" id="channel_content"><?php echo $meeting_info['supplier']; ?></div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="feast_discount">
                <span class="label">餐饮折扣：</span>
                <div class="align_r1 dep_content" id="feast_discount"><?php echo $arr_order_data['feast_discount']; ?>折</div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="other_discount">
                <span class="label">其他折扣：</span>
                <div class="align_r1 dep_content" id="other_discount"><?php echo $arr_order_data['other_discount']; ?>折</div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="changefree">
                <span class="label">免零：</span>
                <div class="align_r1 dep_content" id="other_discount"><?php echo $arr_order_data['cut_price']; ?>元</div>
            </li>
        </ul>
    </div>
    <!-- 回款信息 -->
    <!-- 回款信息 -->
    <div class="table_module">
        <h4 class="module_title">回款纪录</h4>
        <table class="mar_b10">
            <tbody>
            <tr>
                <td>定金</td>
                <td>&yen;<?php echo $arr_order_data['feast_deposit']; ?></td>
                <!-- <td><?php /*echo *//*$return_bill['first']['order_date'];*/ ?></td> -->
            </tr>
            <tr>
                <td>中期款</td>
                <td>&yen;<?php echo $arr_order_data['medium_term']; ?></td>
                <!-- <td><?php /*echo *//*$return_bill['second']['order_date'];*/ ?></td> -->
            </tr>
            <tr>
                <td>尾款</td>
                <td>&yen;<?php echo $arr_order_data['final_payments']; ?></td>
                <!-- <td><?php /*echo*/ /*$return_bill['third']['order_date'];*/ ?></td> -->
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 餐饮 -->
    <?php
        if (!empty($arr_wed_feast)) {
    ?>
    <div class="bill_item_module">
        <h4 class="module_title list_more">会议餐</h4>
        <div class="ulist_module">
            <ul class="ulist menu_list">
                <li class="ulist_item">
                    <div class="dishes">
                        <p class="name"><?php echo $arr_wed_feast['name']; ?></p>
                        <p class="desc">
                        </p>
                    </div>
                    <div>
                        <p class="price">&yen;<?php echo $arr_wed_feast['unit_price'] . $arr_wed_feast['unit']; ?></p>
                        <p class="fee"><?php echo $arr_wed_feast['service_charge_ratio'].'%'; ?>服务费</p>
                    </div>
                    <span class="account">X <?php echo $arr_wed_feast['table_num']; ?></span>
                </li>
            </ul>
            <!-- <p class="remark"><i class="t_green">[备注]</i> </p> -->
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_wed_feast['total_price']; ?></p>
                <i class="profits">毛利：&yen;<?php echo $arr_wed_feast['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_wed_feast['gross_profit_rate']*100).'%'; ?></i>
            </div>
        </div>
    </div>
    <?php
        }
    ?>

        <!-- 场地费 -->
    <?php
        if (!empty($arr_changdi_fee)) {
    ?>
    <div class="bill_item_module">
        <h4 class="module_title list_more">场地费</h4>
        <div class="ulist_module">
            <ul class="ulist menu_list">
                <li class="ulist_item">
                    <div class="dishes">
                        <p class="name"><?php echo $arr_changdi_fee['name']; ?></p>
                        <p class="desc">
                        </p>
                    </div>
                    <div>
                        <p class="price">&yen;<?php echo $arr_changdi_fee['unit_price'] . $arr_changdi_fee['unit']; ?></p>
                    </div>
                    <span class="account">X <?php echo $arr_changdi_fee['amount']; ?></span>
                </li>
            </ul>
            <!-- <p class="remark"><i class="t_green">[备注]</i> </p> -->
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_changdi_fee['total_price']; ?></p>
                <i class="profits">毛利：&yen;<?php echo $arr_changdi_fee['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_changdi_fee['gross_profit_rate']*100).'%'; ?></i>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    <!--灯光-->
    <?php
        if (!empty($arr_light)) {
    ?>
    <div class="bill_item_module">
        <h4 class="module_title">灯光</h4>
        <div class="ulist_module">
            <ul class="ulist menu_list">
                <?php
                    foreach ($arr_light as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $light[$key1] = $value1;
                    }
                        ?>
                    <li class="ulist_item">
                        <div class="dishes">
                            <p class="name"><?php echo $light['name']; ?></p>
                        </div>
                        <div>
                            <p class="price">&yen;<?php echo $light['unit_price'] . $light['unit']; ?></p>
                        </div>
                        <span class="account">X <?php echo $light['amount']; ?></span>
                    </li>
                <?php } ?>
            </ul>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_light_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_light_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_light_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    <!--视频-->
    <?php
        if (!empty($arr_video)) {
    ?>
    <div class="bill_item_module">
        <h4 class="module_title">视频</h4>
        <div class="ulist_module">
            <ul class="ulist menu_list">
                <?php
                    foreach ($arr_video as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $video[$key1] = $value1;
                    }
                        ?>
                    <li class="ulist_item">
                        <div class="dishes">
                            <p class="name"><?php echo $video['name']; ?></p>
                        </div>
                        <div>
                            <p class="price">&yen;<?php echo $video['unit_price'] . $video['unit']; ?></p>
                        </div>
                        <span class="account">X <?php echo $video['amount']; ?></span>
                    </li>
                <?php } ?>
            </ul>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_video_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_video_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_video_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
    
    <div class="bottom_fixed_bar">
        <p class="total_right">总价：<i class="t_green">&yen;<?php echo $arr_total['total_price'] ?></i></p>
        <div class="total_left">
            <i><p>利润：&yen;<?php echo $arr_total['gross_profit'] ?></i>
            <i><p>毛利率：<?php echo sprintf("%01.2f", $arr_total['gross_profit_rate']*100).'%' ?></i>
        </div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //返回
        $(".l_btn").on("click", function () {
            var from = <?php echo $_GET['from']; ?>;
            var order_id = <?php echo $_GET['order_id'];?>;

            if( from == 'detail' ){
                location.href = "<?php echo $this->createUrl("design/detail");?>&order_id=" + order_id;
            }else if( form == 'index' ){
                location.href = "<?php echo $this->createUrl("order/index");?>&from=bill&code=<?php if(isset($_SESSION['code'])){echo $_SESSION['code'];};?>&this_order=" + order_id;
            }
            
        });

        //跳推单页面
        $("#channel").on("click", function () {
            location.href = "<?php echo $this->createUrl("plan/chooseChannel", array("from" => "meeting"));?>";
        })

        //跳折扣页面
        $("#feast_discount").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from" => $_GET['from'],"from1" => "meeting","order_id" => $_GET['order_id'],"type" => "feast"));?>";
        });
        $("#other_discount").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from" => $_GET['from'],"from1" => "meeting","order_id" => $_GET['order_id'],"type" => "meeting_other"));?>";
        });

        //跳折扣页面
        $("#changefree").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/changefree", array("from" => $_GET['from'],"from1" => "meeting","order_id" => $_GET['order_id']));?>";
        });

        //判断订单状态，改变审批按钮
        <?php     //background data
        $order_state = "examined";?>
        var order_state = "<?php echo $order_state;?>"    //php取数
        if (order_state == "non_examine") {
            $("#examining").addClass("hid");
            $("#examined").addClass("hid");
        } else if (order_state == "examining") {
            $("#non_examine").addClass("hid");
            $("#examined").addClass("hid");
        } else if (order_state == "examined") {
            $("#non_examine").addClass("hid");
            $("#examining").addClass("hid");
        };
    })
</script>
</body>
</html>
