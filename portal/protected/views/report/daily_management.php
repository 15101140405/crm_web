<!DOCTYPE html>
<html>

<head>
    <title>经营日报</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base_daily_management.css">
    <link rel="stylesheet" type="text/css" href="css/daily_management.css">

</head>

<body style="background:#4b4b57">
    <h1 class="title"><?php echo $hotel_name?>［<?php echo date("Y-m-d",time())?>］</h1>
    <!--导航-->
    <ul class="navlist flexbox">
        <li id="today_management">
            <a href="javascript:;">
                <img src="images/today_management.png" alt="">
                <p>今日简报</p>
            </a>
        </li>
        <li id="order_list">
            <a href="javascript:;">
                <img src="images/order_list.png" alt="">
                <p>待执行订单</p>
            </a>
        </li>
    </ul>
    <!--内容区1-->
    <section class="daily_container">
        <div class="flexbox v_center title_box">
            <img src="images/today_gl.png" alt="">
            <h2 class="green">今日经营概览</h2>
        </div>
        <ul class="daily_list">
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/today_gl.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $indoor;?></em>个</p>
                    <p>今日进店</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/today_gl.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $open_order?></em>个</p>
                    <p>今日开单</p>
                </div>
            </li>
        </ul>
    </section>
    <!--内容区1-->
    <section class="daily_container">
        <div class="flexbox v_center title_box">
            <img src="images/total_jb.png" alt="">
            <h2 class="green">累计订单简报［<?php echo date('Y')?>～］</h2>
        </div>
        <ul class="daily_list">
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/total_jb.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $wedding_all?></em>个</p>
                    <p>婚礼累计成单</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/total_jb.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $meeting_all?></em>个</p>
                    <p>会议累计成单</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/total_jb.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $wedding_doing?></em>个</p>
                    <p>待执行－婚礼</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/total_jb.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $meeting_doing?></em>个</p>
                    <p>待执行－会议</p>
                </div>
            </li>
        </ul>
    </section>

    <!--内容区1-->
    <section class="daily_container">
        <div class="flexbox v_center title_box">
            <img src="images/sales_detail.png" alt="">
            <h2 class="green">年度销售额［<?php echo date('Y')?>］</h2>
        </div>
        <ul class="daily_list">
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/sales_detail.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo number_format($hotel_total_sales/10000,1)?></em>万</p>
                    <p>应收总额</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/sales_detail.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo number_format($order_total_payment/10000,1)?></em>万</p>
                    <p>实收总额</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/sales_detail.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo number_format($hotel_total_cost/10000,1)?></em>万</p>
                    <p>实际支出</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/sales_detail.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo number_format(($order_total_payment-$hotel_total_cost)/10000,1)?></em>万</p>
                    <p>现金结余</p>
                </div>
            </li>
        </ul>
    </section>
    <!--内容区2-->
    <!-- <section class="daily_container">
        <div class="flexbox v_center title_box">
            <img src="images/sales_detail.png" alt="">
            <h2 class="blue flex1">销售详情（<?php echo date('Y')?>）</h2>
            <img src="images/set01.png" alt="">
        </div>
        <div class="flexbox">
            <ul class="target_list">
                <li class="flexbox">
                    <div class="img_box">
                        <img src="images/sales_detail.png" alt="">
                    </div>
                    <div class="info_box">
                        <p>目标</p>
                        <p><em><?php echo number_format($hotel_target,1)?></em>万</p>
                    </div>
                </li>
                <li class="flexbox">
                    <div class="img_box">
                        <img src="images/sales_detail.png" alt="">
                    </div>
                    <div class="info_box">
                        <p>销售额</p>
                        <p><em><?php echo number_format($hotel_total_sales/10000,1)?></em>万</p>
                    </div>
                </li>
                <li class="flexbox">
                    <div class="img_box">
                        <img src="images/sales_detail.png" alt="">
                    </div>
                    <div class="info_box">
                        <p>回款</p>
                        <p><em><?php echo number_format($order_total_payment/10000,1)?></em>万</p>
                    </div>
                </li>
            </ul>
            <div class="flex1">
                <div id="main" class="main" style="height:300px;"></div>
            </div>
        </div>


    </section> -->

    <!--内容区1-->
    <section class="daily_container daily_list_c">
        <ul class="tab_nav flexbox">
            <li>排名</li>
            <li>员工姓名</li>
            <li>业绩</li>
        </ul>
        <ul class="term_list" id="staff_sales">
    <?php $i = 1; foreach ($arr_staff_sales as $key => $value) {?>
            <li class="term_item">
                <a class="flexbox v_center" href="javascript:;">
                    <div class="item_box flex1">
                        <div class="flexbox v_center"><?php echo $i++;?><img class="me_icon" src="images/last_img.png" alt=""></div>
                        <div><?php echo $value['name']?></div>
                        <div><?php echo $value['sales']?></div>
                    </div>
                    <!-- <img class="arrow" src="images/arrow_right.png" alt=""> -->
                </a>
            </li>
    <?php }?>
        </ul>
    </section>

    <script src="js/echarts.js"></script>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
$(function() {
    //导航
    $("#today_management").on("click",function(){
        location.href = "<?php echo $this->createUrl('report/daily_management');?>&account_id=<?php echo $_GET['account_id']?>&staff_hotel_id=<?php echo $_GET['staff_hotel_id']?>";
    });
    $("#order_list").on("click",function(){
        location.href = "<?php echo $this->createUrl('report/order_list');?>&account_id=<?php echo $_GET['account_id']?>&staff_hotel_id=<?php echo $_GET['staff_hotel_id']?>";
    });

    //末尾标示
    $("#staff_sales li:not(':last-child')").find(".me_icon").css('visibility','hidden');
})
</script>
</body>

</html>