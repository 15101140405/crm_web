<!DOCTYPE html>
<html>

<head>
    <title>订单</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base1.css">
    <link rel="stylesheet" type="text/css" href="css/zepto.aslider.css">
    <link rel="stylesheet" type="text/css" href="css/mobiscroll.css">
    <link rel="stylesheet" type="text/css" href="css/mobiscroll_002.css">
    <link rel="stylesheet" type="text/css" href="css/mobiscroll_003.css">
    <link rel="stylesheet" type="text/css" href="css/order.css">

</head>

<body>  
    <!--顶部筛选-->
    <!-- <section class="filter_area">
        <div class="filter_exit flexbox">
            <div class="all flex1" data-flag="0">全部<img src="images/arrow.png" alt="">
            </div>
            <div class="time flexcenter" data-aslider-in="time_filter|fade">
                <img src="images/time_icon.png" alt="">
            </div>
        </div>
        <div class="mask"></div>
        <ul class="filter_class">
            <li>公共筛选</li>
            <li>
                <span class="radio checked" data-radio="1"></span> 全部
            </li>
            <li>
                <span class="radio" data-radio="0"></span> 签订未付款
            </li>
            <li>
                <span class="radio" data-radio="0"></span> 全部
            </li>
        </ul>
    </section> -->
    <!--订单列表-->
    <ul class="order_list" style="">
<?php foreach ($order_data as $key => $value) {?>
        <li order-type="<?php echo $value['order_type']?>" order-id="<?php echo $value['id']?>">
            <h3><?php echo $value['order_name']?></h3>
            <div class="info flexbox flexcenter_v">
                <div class="flex1" order-status="<?php echo $value['order_status']?>">
                    <span class="tag " ></span>
                </div>

                <div class="con flex1">
                    <p class="flexbox"><img src="images/man_icon.png" alt="">负责人</p>
                    <p><?php echo $value['planner_name']?></p>
                </div>
                <div class="con flex1">
                    <p class="flexbox"><img src="images/time_s_icon.png" alt="">订单日期</p>
                    <p><?php echo $value['order_date']?></p>
                </div>
            </div>
        </li>
<?php }?>
    </ul>
    <!--悬浮按钮-->
    <div class="add_btn"></div>
    <!--侧滑时间筛选-->
    <aside class="aslider time_filter_aslider" data-aslider="time_filter">
        <div class="wrapper">
            <h2>按时间筛选</h2>
            <div class="slider">
                <ul class="time_list">
                    <li>创建时间</li>
                    <li class="in flexbox">
                        <span>开始时间</span>
                        <input type="text" name="appDates_tart" id="appDate_start" placeholder="年月日">
                    </li>
                    <li class="in flexbox">
                        <span>结束时间</span>
                        <input  type="text" name="appDate_end" id="appDate_end" placeholder="年月日">
                    </li>
                </ul>
                <button class="close">确定</button>
            </div>
        </div>
    </aside>

    <script type="text/javascript" src='js/zepto.min.js'></script>
    <script type="text/javascript" src='js/base.js'></script>
    <script type="text/javascript" src='js/jquery.js'></script>
    <script type="text/javascript" src='js/mobiscroll_002.js'></script>

    <script type="text/javascript" src='js/mobiscroll_004.js'></script>
    <script type="text/javascript" src='js/mobiscroll.js'></script>
    <script type="text/javascript" src='js/mobiscroll_003.js'></script>
    <script type="text/javascript" src='js/nav.js'></script>
    <script type="text/javascript" src='js/order.js'></script>


    
<script>
$(function  () {
    //订单状态初始化
    $("[order-status = '1']").find('span').addClass("green").html("预定");
    $("[order-status = '2']").find('span').addClass("blue").html("已交订金");
    $("[order-status = '3']").find('span').addClass("blue").html("已付中期款");
    $("[order-status = '4']").find('span').addClass("blue").html("已付尾款");
    $("[order-status = '5']").find('span').addClass("yellow").html("结算中");
    $("[order-status = '6']").find('span').addClass("gray").html("已完成");

    // li点击跳转
    $(".order_list li").on("click",function(){
        if ("<?php echo $_GET['tab'];?>" == "set") {
            data = {
                'set_id' : '<?php echo $_GET['product_id']?>',
                'order_id' : $(this).attr("order-id"),
                'order_type' : $(this).attr("order-type"),
            };
            $.post("<?php echo $this->createUrl("product/insert_order_set")?>",data,function(){
                alert("添加套系成功！");
                location.href = "<?php echo $this->createUrl('product/store')."&account_id=".$_SESSION['account_id']."&staff_hotel_id=".$_SESSION['staff_hotel_id'];?>";
            });
        } else{
            location.href = "<?php echo $this->createUrl('design/tpDetail');?>&product_id=<?php echo $_GET['product_id']?>&type=new&tab=<?php echo $_GET['tab']?>&from=<?php echo $_GET['from']?>&order_id=" + $(this).attr("order-id");
        };

    });

    //新增订单
    $(".add_btn").on("click",function(){
            location.href = "<?php echo $this->createUrl("product/createorder", array());?>&product_id=<?php echo $_GET['product_id']?>&from=<?php echo $_GET['from']?>";
    });
})
</script>
</body>

</html>