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
    <h1 class="title"><?php echo $hotel['name']?>［<?php echo date("Y-m-d",time())?>］</h1>
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
    <section class="daily_container daily_list_c">
        <ul class="tab_nav flexbox">
            <li>订单日期</li>
            <li>统筹／策划</li>
            <li>推单</li>
        </ul>
        <ul class="term_list">
    <?php foreach ($order_list as $key => $value) {?>
            <li class="term_item">
                <a class="flexbox v_center" href="javascript:;">
                    <div class="item_box flex1">
                        <div class="flexbox v_center"><?php echo $value['order_date']?><img class="me_icon" src="images/<?php if($value['order_type']==1){echo "meeting.png";}else if($value['order_type']==2){echo "wedding.png";}?>" alt=""></div>
                        <div><?php echo $value['pd']?></div>
                        <div><?php echo $value['tuidan']?></div>
                    </div>
                    <img class="arrow" src="images/arrow_right.png" alt="">
                </a>
            </li>
    <?php }?>
        </ul>
    </section>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
$(function() {
    $("#today_management").on("click",function(){
        location.href = "<?php echo $this->createUrl('report/daily_management');?>&account_id=<?php echo $_GET['account_id']?>&staff_hotel_id=<?php echo $_GET['staff_hotel_id']?>";
    });
    $("#order_list").on("click",function(){
        location.href = "<?php echo $this->createUrl('report/order_list');?>&account_id=<?php echo $_GET['account_id']?>&staff_hotel_id=<?php echo $_GET['staff_hotel_id']?>";
    });
})
</script>

</body>

</html>