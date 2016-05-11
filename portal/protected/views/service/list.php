<!DOCTYPE html>
<html>

<head>
    <title>婚礼主持人</title>
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

<body style="background:#fff">
    <!--导航-->
    <!-- <nav class="fixed_nav" id="main_nav">
        <ul>
            <li id="product_store" class="active">
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
            <li id="finance_report">
                <span></span>
                <p class="cat_name">我的业绩</p>
            </li>
        </ul>
    </nav> -->
    <!--主持人列表-->
    <section class="compere_container">
        <ul class="compere_list" id="person">
<?php foreach ($service_data as $key => $value) {?>
            <li class="flexbox v_center" starting-price="<?php echo $value['starting_price']?>" gender="<?php echo $value['gender']?>" service-person-id="<?php echo $value['id']?>">
                <img class="compere_img" src="<?php echo $value['avatar']?>" />
                <div class="flex1 info_box">
                    <h3 class="tit"><?php echo $value['name']?><span><?php echo $value['team_name']?></span></h3>
                    <div class="flexbox info">
                        <p>接单数：<span><?php echo $value['order_num']?></span></p>
                        <p>起价：<span>&yen;<?php echo $value['starting_price']?></span></p>
                    </div>
                </div>
            </li>
<?php }?>
        </ul>
    </section>

    <!--悬浮按钮-->
    <div class="add_btn1" data-aslider-in="filter_aslider|fade"><a href="javascript:;"></a></div>
    <!--侧滑筛选-->
    <aside class="aslider filter_aslider front" data-aslider="filter_aslider">
        <div class="wrapper">
            <div class="tit_box flexbox v_center">
                <img class="close" src="images/close.png" />
                <h2 class="flex1">筛选</h2>
            </div>
            <div class="slider">
                <div class="flexbox filter_container">
                    <ul class="filter_condition">
                        <li class="active">价格</li>
                        <li>时间</li>
                        <li>团队</li>
                        <li>性别</li>
                    </ul>
                    <div class="filter_content flex1">
                        <div class="content">
                            <ul class="filter_list" id="select_price">
                                <li class="filter_item active filter_item1" data-flag="1" id="price">
                                    <span class="flex1">不限</span>
                                    <img src='images/selected.png' />
                                </li>
                                <li class="filter_item"  data-flag="0" price-bottom="0" price-top="1000">&yen;<span class="flex1">1000以下</span></li>
                                <li class="filter_item"  data-flag="0" price-bottom="1000" price-top="3000">&yen;<span class="flex1">1000-3000</span></li>
                                <li class="filter_item"  data-flag="0" price-bottom="3000" price-top="5000">&yen;<span class="flex1">3000-5000</span></li>
                                <li class="filter_item"  data-flag="0" price-bottom="5000" price-top="~">&yen;<span class="flex1">5000以上</span></li>
                            </ul>
                        </div>
                        <div class="content hide">
                            <ul class="filter_list" id="select_date">
                                <li class="filter_item">
                                    <span class="flex1">订单日期</span>
                                    <input class="filter_date" type="text" name="appDates_start" id="appDate" placeholder="年月日" readonly="">
                                </li>
                            </ul>
                        </div>
                        <div class="content hide">
                            <ul class="filter_list" id="select_team">
                                <li class="filter_item active  filter_item1" data-flag="1" id="team">
                                    <span class="flex1">不限</span>
                                    <img src='images/selected.png' />
                                </li>
                            <?php foreach ($service_team as $key => $value) {?>
                                <li class="filter_item"  data-flag="0" selected-team-id="<?php echo $value['id']?>"><span class="flex1"><?php echo $value['name']?></span></li>
                            <?php }?>
                            </ul>
                        </div>
                        <div class="content hide">
                            <ul class="filter_list" id="select_gender">
                                <li class="filter_item active filter_item1" data-flag="1" id="gender">
                                    <span class="flex1">不限</span>
                                    <img src='images/selected.png' />
                                </li>
                                <li class="filter_item"  data-flag="0" selected-gender="1"><span class="flex1">男</span></li>
                                <li class="filter_item"  data-flag="0" selected-gender="0"><span class="flex1">女</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="btn_box flexbox">
                <button class="clear_btn">清空</button>
                <button class="sure_btn flex1 close" id="sure">确定</button>
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
    $(function () {

        //导航gen
        $("#product_store").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/store');?>&code=&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
        });
        $("#index").on("click",function(){
            location.href = "<?php echo $this->createUrl('order/index');?>&from=";
        });
        $("#order").on("click",function(){
            location.href = "<?php echo $this->createUrl('order/order');?>";
        });
        $("#finance_report").on("click",function(){            
            location.href = "<?php echo $this->createUrl('report/financereport');?>";
        });

        //筛选
        $("#sure").on("click",function(){
            var info = {
                'date' : "",
                'team' : "",
                'gender' : "",
            };
            $("#person li").removeAttr("style");

            if($("#appDate").val() != ""){
                info['date'] = $("#appDate").val()
            }; 

            if(!$("#team").hasClass("active")){
                info['team'] = $("#select_team").find(".active").attr("selected-team-id");
            };

            if(!$("#gender").hasClass("active")){
                info['gender'] = $("#select_gender").find(".active").attr("selected-gender");  
            };
            console.log(info);
            if(info['date']!=""||info['team']!=""||info['gender']!=""){
                $.post("<?php echo $this->createUrl('service/datefilter');?>",info,function(retval){
                    var t = new Array();
                    t = retval.split(',');
                    t.pop();
                    for(i = 0 ; i < t.length ; i++){
                        $("[service-person-id='"+t[i]+"']").css("display","none");
                    };
                });
            }
                

            if(!$("#price").hasClass("active")){
                var bottom = $('#select_price').find(".active").attr("price-bottom");
                var top = $('#select_price').find(".active").attr("price-top");
                if(top != "~"){
                    $("#person li").each(function(){

                        if($(this).attr("starting-price")>top || $(this).attr("starting-price")<bottom){
                            $("[service-person-id='"+$(this).attr('service-person-id')+"']").css("display","none");
                        };
                    });    
                }else{
                    $("#person li").each(function(){
                        if($(this).attr("starting-price")<bottom){
                            $(this).css("display","none");
                        };
                    });
                };
            };

        })

        //跳转详情页
        $("#person li").on("click",function(){
            location.href = "<?php echo $this->createUrl('service/personnel_host');?>&from=design&service_person_id="+$(this).attr("service-person-id");
        });

    })
</script>
</body>

</html>