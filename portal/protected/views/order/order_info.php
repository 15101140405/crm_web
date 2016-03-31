<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单列表－订单详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/framework7.ios.min.css">
    <link rel="stylesheet" href="css/framework7.ios.colors.min.css">
    <link rel="stylesheet" href="css/framework7.material.colors.min.css">
    <link rel="stylesheet" href="css/upscroller.css">
    <link rel="stylesheet" href="css/my-app.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/swiper.min.css">
</head>
<body>
<article id="homepage" >
    <div class="tool_bar fixed">
        <!-- <div class="l_btn" data-icon="&#xe69c;" id="filter"></div> -->
        <h2 class="page_title" id="pa_title">订单详情</h2>
        <!--管理层显示该title -->
        <!-- <div class="r_btn" data-icon="&#xe767;"></div> -->
    </div>
    <ul class="m-index-list" id="page_list">
        <li class="card " category="appear" status="进行中">
            <h6  style="text-align: center;color:#37CB58;font-size: 2rem;"><?php echo $order_data['order_name']?></h6>
            <h6  style="text-align: center;color:#b6babf;font-size: 1.2rem;">婚礼日期：<?php echo $order_data['order_date']?></h6>
        </li>
        <li class="card list_more" category="appear" status="进行中" id='follow'>
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 1.8rem;text-align: left;margin-left: 10px;">跟进情况 <span style="color:#b6babf;font-size:1rem;"> [<?php echo $order_data['designer_name'];?>]</span></h6>
            <div class="m-money clear" style="display:inline">
                <div style="display:inline-block;float:left;margin-left:15%;border-right:1px solid #ebebeb;width:35%">
                    <p class="m-money-per " >进店面谈</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;"><?php echo $in_door;?> <i class="m-money-per "> 次</i></p>
                </div>
                <div style="display:inline-block;float:right;margin-right:15%;">
                    <p class="m-money-per ">跟单</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;"><?php echo $out_door;?> <i class="m-money-per "> 次</i></p>
                </div>
            </div>
        </li>
        <li class="card list_more" category="appear" status="进行中" id="now_bill">
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 2rem;" id="offer">当前报价</h6>
            <div class="m-money clear">
                <p class="m-money-num fl" >￥<span><?php echo $order_total['total_price']?></span></p>
                <p class="m-money-per fr">利润率：<span rate="1746"><?php echo $order_total['gross_profit_rate']*100?>%</span>%</p>
            </div>
            <div class="progress"><span class="progress-now fl" style="width:100%;"></span></div>
            <div class="target">
                <p class="target-money fl">成本：￥<span><?php echo $order_total['total_cost']?></span></p>
                <p class="rest-time fr">利润：￥<span class="show_time_1746"><?php echo $order_total['gross_profit']?></span></p>
            </div>
        </li>
        <li class="card " category="appear" status="进行中" id="sure_bill">
            <h6 class="m-list-tit" style="color:#37CB58;;font-size: 1.8rem;text-align: left;margin-left: 10px;">成交</h6>
            <div class="m-money clear" style="display:inline">
                <div style="display:inline-block;float:left;margin-left:5%;">
                    <p class="m-money-per ">成交金额</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;">￥<span><?php echo $order_total['total_price']?></span></p>
                    <p class="m-money-per ">利润率：<i><?php echo $order_total['gross_profit_rate']*100?>%</i></p>
                </div>
                <div style="display:inline-block;float:right;margin-right:15%;">
                    <p class="m-money-per ">累计回款</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;" >￥<span><?php echo $total_payment?></span></p>
                    <p class="m-money-per ">回款比例：<i><?php echo $payment_rate*100?>%</i></p>
                </div>
            </div>
        </li>
    </ul>
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="checkout">申请结算</div>
        <div class="r_btn" id="agree">同意</div>
        <div class="r_btn " id="refuse" style='background-color:#da0f22;'>拒绝</div>
        <div class="r_btn " id="checkingout" style='background-color:#b6babf'>结算中</div>
        <div class="r_btn " id="checkedout" style='background-color:#b6babf'>已完成</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script>
    $(function(){
<?php 
    $newstr = rtrim($order_data['user_department_list'], "]");
    $newstr = ltrim($order_data['user_department_list'], "[");
    $arr_type = explode(",",$newstr);
    $t = 0;
    foreach ($arr_type as $key => $value) {
        if($value == 5){
            $t++;
        }
    };
    if($t == 0){
?>
        //跳转到跟单页面
        $("#follow").on('click',function(){
            location.href='<?php echo $this->createUrl("order/orderinfofollow");?>&order_id=<?php echo $_GET["order_id"]?>';
        });

        //跳转到当前报价（bill）
        $("#now_bill").on("click",function(){
            location.href='<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET["order_id"]?>&from=orderinfo';
        });

        //点击申请结算
        $("#checkout").on("click",function(){
            $.post("<?php echo $this->createUrl('order/checkoutpost');?>",{order_id:<?php echo $_GET['order_id']?>},function(){
                location.reload();
            });
        });

        //初始渲染
        var order_status=<?php echo $order_data['order_status'];?>;
        if(order_status == 0 || order_status == 1){//预定、待定
            $("#bottom").remove();
        };
        if(order_status == 2 || order_status == 3 ){//已付订金、已付中期款
            $("#bottom").remove();
            $("#sure_bill").addClass('list_more');
            /*$("#now_bill").remove();*/
            $("#sure_bill").on("click",function(){
                location.href='<?php echo $this->createUrl("finance/cashierlist");?>&order_id=<?php echo $_GET["order_id"]?>&from=orderinfo';
            });
        };
        if (order_status == 4) {//已付尾款
            $("#offer").html("最终报价");
            $("#agree").remove();
            $("#refuse").remove();
            $("#checkingout").remove();
            $("#checkedout").remove();
        };
        if(order_status == 5){//结算中
            $("#agree").remove();
            $("#refuse").remove();
            $("#checkout").remove();
            $("#checkedout").remove();
        };
<?php
    }else{
?>
        //点击同意结算
        $("#agree").on("click",function(){
            //console.log({order_id:<?php echo $_GET['order_id']?>,final_price:<?php echo $order_total['total_price']?>,final_cost:<?php echo $order_total['total_cost']?>,final_profit:<?php echo $order_total['gross_profit']?>,final_profit_rate:<?php echo $order_total['gross_profit_rate']?>,final_payment:<?php echo $total_payment?>});
            $.post("<?php echo $this->createUrl('order/checkoutagree');?>",{order_id:<?php echo $_GET['order_id']?>,final_price:<?php echo $order_total['total_price']?>,final_cost:<?php echo $order_total['total_cost']?>,final_profit:<?php echo $order_total['gross_profit']?>,final_profit_rate:<?php echo $order_total['gross_profit_rate']?>,final_payment:<?php echo $total_payment?>},function(){
                location.reload();
            });
        });
        //点击拒绝结算
        $("#refuse").on("click",function(){
            //console.log({order_id:<?php echo $_GET['order_id']?>,final_price:<?php echo $order_total['total_price']?>,final_cost:<?php echo $order_total['total_cost']?>,final_profit:<?php echo $order_total['gross_profit']?>,final_profit_rate:<?php echo $order_total['gross_profit_rate']?>,final_payment:<?php echo $total_payment?>});
            $.post("<?php echo $this->createUrl('order/checkoutrefuse');?>",{order_id:<?php echo $_GET['order_id']?>},function(){
                location.reload();
            });
        });

        //初始渲染
        var order_status=<?php echo $order_data['order_status'];?>;
        if(order_status == 0 || order_status == 1 || order_status == 2 || order_status == 3 || order_status == 4){//预定、待定
            $("#bottom").remove();
            $(".list_more").removeClass("list_more");
        };
        if(order_status == 5){//结算中
            $("#checkingout").remove();
            $("#checkout").remove();
            $("#checkedout").remove();
        };
<?php 
    };
?>
        if(order_status == 6){//已结算
            $("#agree").remove();
            $("#refuse").remove();
            $("#checkingout").remove();
            $("#checkout").remove();
        };
    });
</script>

</body>
</html>
