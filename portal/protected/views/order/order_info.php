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
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 2rem;">当前报价</h6>
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
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;" >￥<span></span></p>
                    <p class="m-money-per ">回款比例：<i></i></p>
                </div>
            </div>
        </li>
    </ul>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script>
    $(function(){
        //跳转到跟单页面
        $("#follow").on('click',function(){
            location.href='<?php echo $this->createUrl("order/orderinfofollow");?>&order_id=<?php echo $_GET["order_id"]?>';
        });

        //跳转到当前报价（bill）
        $("#now_bill").on("click",function(){
            location.href='<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET["order_id"]?>&from=orderinfo';
        });
        
        //初始渲染
        var order_status=1;
        if(order_status != 0 && order_status != 1 ){
            $("#sure_bill").addClass('list_more');
            $("#now_bill").remove();
            $("#sure_bill").on("click",function(){
                location.href='<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET["order_id"]?>&from=orderinfo';
            });

        }
    });
</script>

</body>
</html>
