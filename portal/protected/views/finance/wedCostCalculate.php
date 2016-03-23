<?php
$arr_locate = array(
                'img' => 'meeting_layout.jpg',

            );

?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>［订单］成本核算</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="css/staff_management.css" rel="stylesheet" type="text/css"/>

</head>
<body>
<article style='position: relative;bottom: 100px;'>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">［订单］成本核算</h2>

    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item">
                <span class="big_font">婚礼信息</span>
                <a class="singup right btn_disable" id="print" href="">打印</a>
                <a class="singup right btn_red" id="examined" href="">审批通过</a>
                <a class="singup right btn_disable" id="examining" href="">审批中</a>
                <a class="singup right" id="non_examine" href="">核算审批</a>
            </li>
            <li class="ulist_item">婚礼日期：<?php echo $arr_order_data['order_date']; ?></li>
            <li class="ulist_item">订单名称：<?php echo $arr_order_data['order_name']; ?></li>
            <li class="ulist_item">策 划 师：<?php echo $designer; ?></li>
            <li class="ulist_item">统 筹 师：<?php echo $planner; ?></li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item  department" id="channel">
                <span class="label">推单返点：</span>
                <div class="align_r dep_content" id="channel_content"></div>
            </li>
            <li class="ulist_item  department" id="channel">
                <span class="label">统筹师提成：</span>
                <div class="align_r dep_content" id="channel_content"></div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item  department" id="channel">
                <span class="label">策划师提成：</span>
                <div class="align_r dep_content" id="channel_content"></div>
            </li>
            <li class="ulist_item  department" id="channel">
                <span class="label">策划师策划费：</span>
                <div class="align_r dep_content" id="channel_content"></div>
            </li>
        </ul>
    </div>

    <!-- 回款信息 -->
    <div class="table_module list_more" id="shoukuan">
        <h4 class="module_title">收款记录</h4>
        <table class="mar_b10">
            <tbody>
            <tr>
                <td>订金</td>
                <td class="t_green">&yen;<?php echo $order_cashier['feast_deposit']; ?></td>
                <td><?php /*echo $return_bill['first']['order_date'];*/ ?></td>
            </tr>
            <tr>
                <td>中期款</td>
                <td class="t_green">&yen;<?php echo $order_cashier['medium_term']; ?></td>
                <td><?php /*echo $return_bill['first']['order_date'];*/ ?></td>
            </tr>
            <tr>
                <td>尾款</td>
                <td class="t_green">&yen;<?php echo $order_cashier['final_payments']; ?></td>
                <td><?php /*echo $return_bill['third']['order_date']; */?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table_module" id="payment_total">
        <h4 class="module_title">结账记录</h4>
        <table class="mar_b10">
            <tbody>
            <tr>
                <td>应付账款</td>
                <td class="t_green">&yen;<?php echo $order_total; ?></td>
                <td><?php /*echo $return_bill['first']['order_date'];*/ ?></td>
            </tr>
            <tr>
                <td>未付账款</td>
                <td class="t_green">&yen;<?php echo $order_total-$order_paid; ?></td>
                <td><?php /*echo $return_bill['third']['order_date']; */?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 餐饮 -->
    <?php
        if (!empty($arr_supplier_finance)) {
            foreach ($arr_supplier_finance as $key => $value) {                      
    ?>
    <div class="bill_item_module">
        <h4 class="module_title list_more" typeId="<?php echo $value['supplier_type_id'];?>" supplierId="<?php echo $value['supplier_id'];?>"><?php echo $value['supplier_name'];?></h4>
        <div class="ulist_module">
    <?php
                    foreach ($value['product'] as $key1 => $value1) {
    ?>
            <ul class="ulist menu_list">
                <li class="ulist_item" style="border-bottom: 1px solid #eee;">
                    <div class="dishes">
                        <p class="name"><?php echo $value1['product_name']; ?></p>
                        <p class="desc">
                        </p>
                    </div>
                    <div>
                        <p class="price">&yen;<?php echo $value1['actual_unit_cost'] . $value1['unit']; ?></p>
                    </div>
                    <span class="account">X <?php echo $value1['amount']; ?></span>
                </li>
            </ul>
    <?php
                    }
    ?>
            <!-- <p class="remark"><i class="t_green">[备注]</i> </p> -->
            <div class="subtotal">
                <p>费用合计：&yen;<?php echo $value['supplier_total_cost']; ?></p>
                <i class="profits">已付金额：&yen;<?php echo $value['supplier_total_paid']; ?></i>
                <i class="profits">欠款：&yen; <span class='unpaid'><?php echo $value['supplier_total_cost']-$value['supplier_total_paid']; ?></span></i>
            </div>
        </div>
    </div>
    <?php
                            
                        
                    }
                }
    ?>   


    <div class="bottom_fixed_bar">
        <?php
            if(!empty($order_total)){
        ?>
        <p class="total_right">总费用：<i class="t_green">&yen;<?php echo $order_total ?></i></p>
        <div class="total_left">
            <i><p>已付金额：&yen;<?php echo $order_paid ?></i>
            <i><p>欠款：&yen;<?php echo $order_total-$order_paid ?></i>
        <?php }?>  
        </div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //返回
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl('design/detail');?>";
        });

        //跳收款页面
        $("#shoukuan").on("click", function () {
            location.href = "<?php echo $this->createUrl('finance/cashier');?>&order_id=<?php echo $_GET['order_id'] ?>&from=<?php echo $_GET['from'] ?>";
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
        }
        ;

        //隐藏非餐饮供应商的服务费
        var html ="<p class='fee'><?php echo $value1['actual_service_ratio']; ?>服务费</p>"
        $("[typeid='2']").find('.price').after(html);

        //点击供应商，跳付款／详情页面
        $("[supplierId]").on("click",function(){
            location.href = "<?php echo $this->createUrl('finance/payment');?>&order_id=<?php echo $_GET['order_id'] ?>&from=<?php echo $_GET['from'] ?>&supplier_id=" + $(this).attr("supplierId") + "&supplier_name=" + $(this).html() + "&unpaid＝" + $(this).parent().find('.unpaid').html();
        })

    });


</script>
</body>
</html>
