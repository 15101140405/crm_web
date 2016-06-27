<!DOCTYPE html>
<html>

<head>
    <title>日报</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base1.css">
    <link rel="stylesheet" type="text/css" href="css/zepto.aslider.css">
    <link rel="stylesheet" type="text/css" href="css/mobiscroll.css">
    <link rel="stylesheet" type="text/css" href="css/mobiscroll_002.css">
    <link rel="stylesheet" type="text/css" href="css/mobiscroll_003.css">
    <link rel="stylesheet" type="text/css" href="css/order.css">
    <link rel="stylesheet" type="text/css" href="css/compere.css">

</head>


<body style="background:#F7F7F7">
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
            <li id="order_count" class="active">
                <span></span>
                <p class="cat_name">订单统计</p>
            </li>
            <li id="hotel_finance_report">
                <span></span>
                <p class="cat_name">本店财报</p>
            </li>
        </ul>
    </nav>
    <!--订单报表-->
    <section class="daily_container">
        <!-- <h2 class="daily_tit">今日进店</h2>
        <ul class="daily_list">
            <li data-aslider-in="aslider1|fade" id="open">
                <img class="item_icon" src="images/today.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">今日开单</span>
                    <span class="num"></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
        </ul>
        <h2 class="daily_tit">本月统计</h2>
        <ul class="daily_list">
            <li data-aslider-in="aslider1|fade" id="wed_m_open">
                <img class="item_icon" src="images/wed_m_open.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">进店婚礼</span>
                    <span class="num"></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
            <li data-aslider-in="aslider1|fade" id="wed_m_pay">
                <img class="item_icon" src="images/wed_m_pay.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">婚礼成单</span>
                    <span class="num"></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
             <li data-aslider-in="aslider1|fade" id="meeting_m_open">
                <img class="item_icon" src="images/meeting_m_open.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">进店会议</span>
                    <span class="num"></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
            <li data-aslider-in="aslider1|fade" id="meeting_m_pay">
                <img class="item_icon" src="images/meeting_m_pay.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">会议成单</span>
                    <span class="num"></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
            </li>
        </ul> -->
        <h2 class="daily_tit">婚礼订单</h2>
        <ul class="daily_list">
            <li data-aslider-in="aslider1|fade" id="wed_finish">
                <img class="item_icon" src="images/wed_finish.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">已完成婚礼</span>
                    <span class="num"><?php echo $order_num['wedding_done']?></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
            <li data-aslider-in="aslider1|fade" id="wed_doing">
                <img class="item_icon" src="images/wed_doing.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">待执行婚礼</span>
                    <span class="num"><?php echo $order_num['wedding_doing']?></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
        </ul>

        <h2 class="daily_tit">会议订单</h2>
        <ul class="daily_list">
            <li data-aslider-in="aslider1|fade" id="meeting_finish">
                <img class="item_icon" src="images/meeting_finish.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">已完成会议</span>
                    <span class="num"><?php echo $order_num['meeting_done']?></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
            <li data-aslider-in="aslider1|fade" id="meeting_doing">
                <img class="item_icon" src="images/meeting_doing.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">待执行会议</span>
                    <span class="num"><?php echo $order_num['meeting_doing']?></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
        </ul>

        <!-- <h2 class="daily_tit" id="clear_title">订单结算</h2>
        <ul class="daily_list" id="clear">
            <li data-aslider-in="aslider1|fade" id="wed_clearing">
                <img class="item_icon" src="images/order_open.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">婚礼结算申请</span>
                    <span class="num"></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
            <li data-aslider-in="aslider1|fade" id="meeting_clearing">
                <img class="item_icon" src="images/order_open.png" />
                <div class="daily_info flex1 flexbox v_center" style="height:2rem;">
                    <span class="flex1">会议结算申请</span>
                    <span class="num"></span>
                    <img class="arrow" src="images/arrow_right.png" />
                </div>
            </li>
        </ul> -->
    </section>


    <!--侧滑筛选-->
    <aside class="aslider filter_aslider" data-aslider="aslider1" style="z-index: 100001;">
        <div class="wrapper">
            <div class="tit_box flexbox v_center">
                <!-- <img class="close" src="images/close.png" /> -->
                <h2 class="flex1">订单列表</h2>
            </div>
            <div class="slider">
                <ul class="aslider_order_list" id="list">
            <?php if(!empty($order_data['wedding_done'])){foreach ($order_data['wedding_done'] as $key => $value) {?>
                    <li style="    border-bottom: none;" class="flexbox" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="2">
                        <span class="flex1"><?php echo $value['order_date']?>[<?php echo $value['order_type']?>]</span>
                        <!-- <span style="color: #bd9d62;float:right;">［应收：<?php echo $value['price']?>元］</span> -->
                        <span style="float: right;display: block;margin-right: 10px;" class="yellow">实际毛利：<?php echo sprintf("%.2f", $value['payment']-$value['cost'])?>元</span>
                    </li>
                    <li  class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="2">
                        <span style="color: #b6babf">［实支：<?php echo sprintf("%.2f", $value['cost'])?>元］</span>
                        <span style="color: #b6babf;float:right;">［实收：<?php echo sprintf("%.2f", $value['payment'])?>元］</span>
                    </li>
                    <!-- <li style="height: 16px;" class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="2">
                    </li> -->
            <?php }}?>
            <?php if(!empty($order_data['wedding_doing'])){foreach ($order_data['wedding_doing'] as $key => $value) {?>
                    <li style="    border-bottom: none;" class="flexbox" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="3">
                        <span class="flex1"><?php echo $value['order_date']?>[<?php echo $value['order_type']?>]</span>
                        <!-- <span style="color: #bd9d62;float:right;">［应收：<?php echo $value['price']?>元］</span> -->
                        <span style="float: right;display: block;margin-right: 10px;" class="yellow">实际毛利：<?php echo sprintf("%.2f", $value['payment']-$value['cost'])?>元</span>
                    </li>
                    <li  class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="3">
                        <span style="color: #b6babf">［实支：<?php echo sprintf("%.2f", $value['cost'])?>元］</span>
                        <span style="color: #b6babf;float:right;">［实收：<?php echo sprintf("%.2f", $value['payment'])?>元］</span>
                    </li>
                    <!-- <li style="height: 16px;" class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="3">
                    </li> -->
            <?php }}?>
            <?php if(!empty($order_data['meeting_done'])){foreach ($order_data['meeting_done'] as $key => $value) {?>
                    <li style="    border-bottom: none;" class="flexbox" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="4">
                        <span class="flex1"><?php echo $value['order_date']?>[<?php echo $value['order_type']?>]</span>
                        <!-- <span style="color: #bd9d62;float:right;">［应收：<?php echo $value['price']?>元］</span> -->
                        <span style="float: right;display: block;margin-right: 10px;" class="yellow">实际毛利：<?php echo sprintf("%.2f", $value['payment']-$value['cost'])?>元</span>
                    </li>
                    <li  class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="4">
                        <span style="color: #b6babf">［实支：<?php echo sprintf("%.2f", $value['cost'])?>元］</span>
                        <span style="color: #b6babf;float:right;">［实收：<?php echo sprintf("%.2f", $value['payment'])?>元］</span>
                    </li>
                    <!-- <li style="height: 16px;" class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="4">
                    </li> -->
            <?php }}?>
            <?php if(!empty($order_data['meeting_doing'])){foreach ($order_data['meeting_doing'] as $key => $value) {?>
                    <li style="    border-bottom: none;" class="flexbox" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="5">
                        <span class="flex1"><?php echo $value['order_date']?>[<?php echo $value['order_type']?>]</span>
                        <!-- <span style="color: #bd9d62;float:right;">［应收：<?php echo $value['price']?>元］</span> -->
                        <span style="float: right;display: block;margin-right: 10px;" class="yellow">实际毛利：<?php echo sprintf("%.2f", $value['payment']-$value['cost'])?>元</span>
                    </li>
                    <li  class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="5">
                        <span style="color: #b6babf">［实支：<?php echo sprintf("%.2f", $value['cost'])?>元］</span>
                        <span style="color: #b6babf;float:right;">［实收：<?php echo sprintf("%.2f", $value['payment'])?>元］</span>
                    </li>
                    <!-- <li style="height: 16px;" class="flexbox block" order-id="<?php echo $value['order_id']?>" order-type="<?php echo $value['order_type']?>" type="5">
                    </li> -->
            <?php }}?>
                </ul>
            </div>
            <div class="btn_box flexbox">
                <button class="sure_btn flex1 close">返回</button>
            </div>
        </div>
    </aside>

    <script type="text/javascript" src='js/zepto.min.js'></script>
    <script type="text/javascript" src='js/base.js'></script>
    <script type="text/javascript" src='js/jquery.1.7.2.min.js'></script>
    <script type="text/javascript" src='js/mobiscroll_002.js'></script>

    <script type="text/javascript" src='js/mobiscroll_004.js'></script>
    <script type="text/javascript" src='js/mobiscroll.js'></script>
    <script type="text/javascript" src='js/mobiscroll_003.js'></script>
    <script type="text/javascript" src='js/nav.js'></script>
    <script type="text/javascript" src='js/order.js'></script>
    <script type="text/javascript" src='js/compere.js'></script>
    <script>
    $(function(){
        //初始渲染
        // if("<?php echo $user_type?>" != 2){
        //     $("#clear").remove();
        //     $("#clear_title").remove();
        // };
        // $("#open").on("click",function(){
        //     $("#list li").removeClass("hid");
        //     $("#list li").addClass("hid");
        //     $("[type='1']").removeClass("hid");
        // });
        $("#wed_finish").on("click",function(){
            $("#list li").removeClass("hid");
            $("#list li").addClass("hid");
            $("[type='2']").removeClass("hid");
        });
        $("#wed_doing").on("click",function(){
            $("#list li").removeClass("hid");
            $("#list li").addClass("hid");
            $("[type='3']").removeClass("hid");
        });
        $("#meeting_finish").on("click",function(){
            $("#list li").removeClass("hid");
            $("#list li").addClass("hid");
            $("[type='4']").removeClass("hid");
        });
        $("#meeting_doing").on("click",function(){
            $("#list li").removeClass("hid");
            $("#list li").addClass("hid");
            $("[type='5']").removeClass("hid");
        });
        // $("#wed_clearing").on("click",function(){
        //     $("#list li").removeClass("hid");
        //     $("#list li").addClass("hid");
        //     $("[type='6']").removeClass("hid");
        // });
        // $("#wed_m_open").on("click",function(){
        //     $("#list li").removeClass("hid");
        //     $("#list li").addClass("hid");
        //     $("[type='11']").removeClass("hid");
        // });
        // $("#meeting_m_open").on("click",function(){
        //     $("#list li").removeClass("hid");
        //     $("#list li").addClass("hid");
        //     $("[type='12']").removeClass("hid");
        // });
        // $("#wed_m_pay").on("click",function(){
        //     $("#list li").removeClass("hid");
        //     $("#list li").addClass("hid");
        //     $("[type='13']").removeClass("hid");
        // });
        // $("#meeting_m_pay").on("click",function(){
        //     $("#list li").removeClass("hid");
        //     $("#list li").addClass("hid");
        //     $("[type='14']").removeClass("hid");
        // });

        //导航
        $("#product_store").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/store');?>&code=&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
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

        // li点击跳转
        $(".aslider_order_list li").on("click",function(){
            order_type = $(this).attr("order-type");
            order_id = $(this).attr("order-id");
            if(order_type == "婚礼"){
                location.href = "<?php echo $this->createUrl("design/bill");?>&order_id=" + order_id + "&from=my_order";
            }else if(order_type == "会议"){
                location.href = "<?php echo $this->createUrl("meeting/bill");?>&order_id=" + order_id + "&from=my_order";
            };
        });
    })
    </script>
</body>

</html>