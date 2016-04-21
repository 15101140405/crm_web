<!DOCTYPE html>
<html>

<head>
    <title>财报</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base1.css">
    <link rel="stylesheet" type="text/css" href="css/order.css">

</head>

<body style="background:#151320">
    <!--导航-->
    <nav class="fixed_nav" id="main_nav">
        <ul>
            <li id="product_store">
                <span></span>
                <p class="cat_name">产品库</p>
            </li>
            <li id="index">
                <span></span>
                <p class="cat_name">档期</p>
            </li>
            <li id="order">
                <span></span>
                <p class="cat_name">订单</p>
            </li>
            <li id="finance_report" class="active">
                <span></span>
                <p class="cat_name">财报</p>
            </li>
        </ul>
    </nav>
    <section class="finance_container">
        <h2 class="title">大郊亭国际商务酒店</h2>
        <div id="progress" class="progress">
            <div class="pro_info">
                <p class="money"><strong><?php echo $sales['deal']?></strong>万元</p>
                <p class="plan">已完成当年目标的<span><?php echo $sales['deal']/$sales['target']*100?>％</span></p>
            </div>
            <p class="pro_bot">年度累计销售额</p>
            <span class="min">0</span>
            <span class="max">100+</span>
        </div>
        <div class="financ_order flexbox">
            <div class="flex1">
                <div class="order_item">
                    <p>已结算毛利</p>
                    <p><strong><?php echo $final_profit?></strong>万元</p>
                </div>
                <div class="order_item">
                    <p>运营成本</p>
                    <p><strong>0</strong>万元</p>
                </div>
            </div>
            <div class="flex1">
                <div class="order_item">
                    <p>累计婚礼成单</p>
                    <p><strong><?php echo $data['wedding']?></strong>场</p>
                </div>
                <div class="order_item">
                    <p>累计会议成单</p>
                    <p><strong><?php echo $data['meeting']?></strong>场</p>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript" src='js/zepto.min.js'></script>
    <script type="text/javascript" src='js/base.js'></script>
    <script type="text/javascript" src='js/nav.js'></script>
<script>
$(function () {
    var pro_num=<?php echo $sales['deal']/$sales['target']*100?>;//用于接收当前进度
    $('#progress').progress({
        initValue: pro_num
        , radius: 120
        , barWidth: 5
        , curbarWidth: 11
        , roundCorner: true
        , barBgColor: '#353743'
        , barColor: '#66d6e4'
        , basecirc: Math.PI * 0.25
        , basequare: Math.PI * 0.75
        , circ: Math.PI * (2 - 0.5)
        , quart: Math.PI * 1.25
        , percentage: false
    , });

    //导航
    $("#product_store").on("click",function(){
        location.href = "<?php echo $this->createUrl('product/store');?>&code=&account_id=1";
    });
    $("#index").on("click",function(){
        location.href = "<?php echo $this->createUrl('order/index');?>&from=&code=&account_id=1";
    });
    $("#order").on("click",function(){
        location.href = "<?php echo $this->createUrl('order/order');?>&account_id=1";
    });
    $("#finance_report").on("click",function(){
        location.href = "<?php echo $this->createUrl('report/financereport');?>&account_id=1&staff_hotel_id=1";
    });
})
</script>
</body>

</html>