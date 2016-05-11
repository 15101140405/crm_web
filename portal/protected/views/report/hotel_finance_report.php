<!DOCTYPE html>
<html>
<head>
    <title>我的业绩</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base1.css">
    <link rel="stylesheet" type="text/css" href="css/order.css">
    <link rel="stylesheet" type="text/css" href="css/zepto.aslider.css">
    <link rel="stylesheet" type="text/css" href="css/compere.css">
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
            <li id="order_count">
                <span></span>
                <p class="cat_name">订单统计</p>
            </li>
            <li id="hotel_finance_report" class="active">
                <span></span>
                <p class="cat_name">本店财报</p>
            </li>
        </ul>
    </nav>
    <section class="finance_container">
        <!-- <h2 class="title">我的业绩</h2> -->
        <div id="progress" class="progress">
            <div class="pro_info" data-aslider-in="filter_aslider|fade">
                <p class="money"><strong><?php echo sprintf("%.2f", $hotel_data['total_sales']/10000) ?></strong>万元</p>
                <p class="plan">已完成当年目标的<span>0.00％</span></p>
            </div>
            <p class="pro_bot">年度累计销售额</p>
            <span class="min">0</span>
            <span class="max">100+</span>
        </div>
        <div class="financ_order flexbox">
            <div class="flex1">
                <div class="order_item">
                    <p>已结算毛利</p>
                    <p><strong><?php echo $hotel_data['final_profit']?></strong>万元</p>
                </div>
                <div class="order_item">
                    <p>运营成本</p>
                    <p><strong><?php echo $hotel_data['operating_costs']?></strong>万元</p>
                </div>
            </div>
            <div class="flex1">
                <div class="order_item">
                    <p>累计婚礼成单</p>
                    <p><strong><?php echo $hotel_data['total_wed']?></strong>场</p>
                </div>
                <div class="order_item">
                    <p>累计会议成单</p>
                    <p><strong><?php echo $hotel_data['total_meeting']?></strong>场</p>
                </div>
            </div>
        </div>
    </section>
    <!--侧滑筛选-->
    <aside class="aslider filter_aslider front" data-aslider="filter_aslider">
        <div class="wrapper">
            <div class="tit_box flexbox v_center">
                <img class="close" src="images/close.png" />
                <h2 class="flex1">月度明细</h2>
            </div>
            <div class="slider" style="margin-bottom:70px;">
                <div class="flexbox filter_container">
                    <div class="filter_content flex1">
                        <div class="content">
                            <ul class="filter_list" id="select_price">
                        <?php $i=0; foreach ($month_order as $key => $value) { $i++;?>
                                <li class="filter_item"  data-flag="0" price-bottom="0" price-top="1000"><span style="margin-left:1rem;" class="flex1"><?php echo $i ?>月</span><span style="color:#bd9d62;" class="yellow">&yen;<?php echo $value/10000?>万元</span></li>
                        <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn_box flexbox">
                <button class="sure_btn flex1 close" id="sure">确定</button>
            </div>
        </div>
    </aside>
    <script type="text/javascript" src='js/zepto.min.js'></script>
    <script type="text/javascript" src='js/base.js'></script>
    <script type="text/javascript" src='js/nav.js'></script>
    <script type="text/javascript" src='js/order.js'></script>
    <script type="text/javascript" src='js/compere.js'></script>
<script>
$(function () {
    var pro_num="";//用于接收当前进度
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
        location.href = "<?php echo $this->createUrl('product/store');?>&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
    });
    $("#index").on("click",function(){
        location.href = "<?php echo $this->createUrl('order/index');?>&from=";
    });
    $("#order_count").on("click",function(){
        location.href = "<?php echo $this->createUrl('report/order_report');?>";
    });
    $("#hotel_finance_report").on("click",function(){
        location.href = "<?php echo $this->createUrl('report/hotel_finance_report');?>";
    });

})
</script>
</body>

</html>