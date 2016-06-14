<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单填写</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <script src="js/jquery.1.7.2.min.js"></script>
    <script src="js/mobiscroll_002.js" type="text/javascript"></script>
    <script src="js/mobiscroll_004.js" type="text/javascript"></script>
    <link href="css/mobiscroll_002.css" rel="stylesheet" type="text/css">
    <link href="css/mobiscroll.css" rel="stylesheet" type="text/css">
    <script src="js/mobiscroll.js" type="text/javascript"></script>
    <script src="js/mobiscroll_003.js" type="text/javascript"></script>
    <script src="js/mobiscroll_005.js" type="text/javascript"></script>
    <link href="css/mobiscroll_003.css" rel="stylesheet" type="text/css">
    <style type="text/css">
        #time input, select {
            width: 100%;
            padding: 5px;
            margin: 5px 0;
            border: 1px solid #aaa;
            box-sizing: border-box;
            border-radius: 5px;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            -webkit-border-radius: 5px;
        }

        .header h1 {
            text-align: center;
            font-size: 16px;
            margin: .6em 0;
            padding: 0;
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
        }
        .content {
            padding: 15px;
            background: #fff;
        }
    </style>
</head>
<body>
<article>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">订单填写</h2>
    </div>
    <div class="content" id="time">
        <div class="demos">
            <label for="appDate">日期</label>
            <input value="" class="" placeholder="请选择日期" readonly="readonly" name="appDate" id="appDate" type="text">
        </div>
        <div class="demos">
            <label for="appTime">开始时间</label>
            <input value="" class="" placeholder="请选择开始时间" readonly="readonly" name="appTime_start" id="appTime" type="text">
        </div>

        <div class="demos">
            <label for="appTime">结束时间</label>
            <input value="" class="" placeholder="请选择结束时间" readonly="readonly" name="appTime_end" id="appTime1" type="text">
        </div>
    </div>
    <div class="ulist_module pad_b40" style="margin-top:10px;" id="price">
        <ul class="ulist">
            <!-- <li class="ulist_item flex">
                价格
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="price" value=""
                           placeholder="请输入价格"/>
                </div>
                <i class="mar_l10 t_green"></i>
            </li> -->
        <?php if($_GET['category'] == 3 || $_GET['category'] == 4){?>
            <li class="ulist_item flex" id="number">
                桌数
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="amount" value=""
                           placeholder="请输入数量"/>
                </div>
            </li>
            <li class="ulist_item flex" id='fuwufei'>
                服务费
                <div class="flex1">
                    <input class="align_r t_green" id="fuwufei_input" type="text" value="" placeholder="请输入服务费" id="fee"/>
                </div>
            </li>
        <?php }else if($_GET['from'] == 'service'){?>
            <li class="ulist_item flex" id="number">
                数量
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="amount" value=""
                           placeholder="请输入数量"/>
                </div>
            </li>
        <?php }?>
            <li class="ulist_item">备注</li> 
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="请输入备注" id="remark"></textarea>
                </div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module" style="margin-top: 10px;" id="groom">
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">新郎姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="新郎姓名" id="groom_name"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="手机号" id="groom_phone"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">微信</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="微信" id="groom_wechat"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">QQ</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="QQ" id="groom_qq"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module" style="margin-top:10px;margin-bottom:70px;" id="bride">
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">新娘姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="新娘姓名" id="bride_name"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="手机号" id="bride_phone"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">微信</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="微信" id="bride_wechat"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">QQ</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="QQ" id="bride_qq"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module" style="margin-top:10px;margin-bottom:70px;" id="wed_link">
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">联系人姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="新娘姓名" id="linkman_name"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="手机号" id="linkman_phone"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="select_ulist_module" style="margin-top:12px;margin-bottom:70px;" id="meeting_data">
        <ul class="select_ulist">
            <li class="select_ulist_item list_more" id="meeting_company">客户公司<span style="float:right;font-size:15px;" class="t_gray" id="meeting_company_name"></span></li>
            <li class="select_ulist_item list_more" id="meeting_link">联系人<span style="float:right;font-size:15px;" class="t_gray" id="meeting_linkman_name"></span></li>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="insert">提交订单</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>

    //不同页面：初始化、返回、保存（获取、验证数据）、删除

    $(function () {
        if(<?php echo $_GET['category']?> == 1 || <?php echo $_GET['category']?> == 4){//会议，删去新人信息部分
            $(".int_ulist_module").remove();
        };
        //选择日期插件
        var currYear = (new Date()).getFullYear();  
        var opt={};
        opt.date = {preset : 'date'};
        opt.datetime = {preset : 'datetime'};
        opt.time = {preset : 'time'};
        opt.default = {
            theme: 'android-ics light', //皮肤样式
            display: 'modal', //显示方式 
            mode: 'scroller', //日期选择模式
            dateFormat: 'yyyy-mm-dd',
            lang: 'zh',
            showNow: true,
            nowText: "今天",
            startYear: currYear - 10, //开始年份
            endYear: currYear + 10 //结束年份
        };

        $("#appDate").mobiscroll($.extend(opt['date'], opt['default']));
        var optDateTime = $.extend(opt['datetime'], opt['default']);
        var optTime = $.extend(opt['time'], opt['default']);
        $("#appDateTime").mobiscroll(optDateTime).datetime(optDateTime);
        $("#appTime").mobiscroll(optTime).time(optTime);
        $("#appTime1").mobiscroll(optTime).time(optTime);

        var new_order_info = {};

        if("<?php echo $_GET['from']?>" == "set"){
            $("#price").remove();
        };
        if("<?php echo $_GET['category']?>" == 1 || "<?php echo $_GET['category']?>" == 4){
            $("#groom").remove();
            $("#bride").remove();
            $("#wed_link").remove();
        }else if("<?php echo $_GET['category']?>" == 2 || "<?php echo $_GET['category']?>" == 3){
            $("#meeting_data").remove();
        };

        <?php if(isset($_GET['company_id'])){?>
            $("#meeting_company_name").html("<?php echo $order_meeting_company?>");
            $("#order_meeting_company_linkman").html("<?php echo $order_meeting_company_linkman?>");
        <?php }?>


        $("#insert").on("click",function(){
            var mydate = new Date();
            var year = mydate.getFullYear() + "";
            var month = mydate.getMonth() + 1;
            var month = month + "";
            var date = mydate.getDate() + "";
            var hours = mydate.getHours() + "";
            var minutes = mydate.getMinutes() + "";
            var seconds = mydate.getSeconds() + "";

            var time = year + "-" + month + "-" + date + " " + hours + "-" + minutes + "-" + seconds;
            var order_date = $("#appDate").val()+" "+$("#appTime").val()+":00";
            var end_time = $("#appDate").val()+" "+$("#appTime1").val()+":00";
            /*if("<?php echo $_GET['from']?>" == "set"){
                $("#price").remove();
                new_order_info = {
                    order_date: order_date,
                    end_time: end_time, 
                    update_time : time,
                    groom_name: $("#groom_name").val(),
                    groom_phone: $("#groom_phone").val(),
                    groom_wechat: $("#groom_wechat").val(),
                    groom_qq: $("#groom_qq").val(),
                    bride_name: $("#bride_name").val(),
                    bride_phone: $("#bride_phone").val(),
                    bride_wechat: $("#bride_wechat").val(),
                    bride_qq: $("#bride_qq").val(),
                    linkman_name : $("#linkman_name").val(),
                    linkman_phone : $("#linkman_phone").val(),
                    set_id : "<?php if (isset($_GET['set_id'])){echo $_GET['set_id'];}?>",
                };
            }else{*/
            new_order_info = {
                order_date: order_date,
                end_time: end_time, 
                update_time : time,
                order_type : <?php 
                             if($_GET['category'] == 1 || $_GET['category'] == 4){
                                echo 1;
                            } else {
                                echo 2;
                            } 
                            ?>,
            <?php if($_GET['category'] == 3 || $_GET['category'] == 4){?>
                amount : $("#amount").val(),
                service_charge_ratio : $("#fuwufei_input").val(),
                remark : $("#remark").val(),
            <?php }else if($_GET['from'] == 'service' ){?>
                amount : $("#amount").val(),
                remark : $("#remark").val(),
                product_id : <?php echo $_GET['product_id']?>,
            <?php }else {?>
                remark : "",
            <?php }?>
                groom_name: $("#groom_name").val(),
                groom_phone: $("#groom_phone").val(),
                groom_wechat: $("#groom_wechat").val(),
                groom_qq: $("#groom_qq").val(),
                bride_name: $("#bride_name").val(),
                bride_phone: $("#bride_phone").val(),
                bride_wechat: $("#bride_wechat").val(),
                bride_qq: $("#bride_qq").val(),
                linkman_name : $("#linkman_name").val(),
                linkman_phone : $("#linkman_phone").val(),
            <?php if($_GET['from'] != "service"){?>
                set_id : "<?php echo $_GET['set_id'];?>",
            <?php }?>
            <?php if(isset($_GET['company_id'])){?>
                company_id : $_GET['company_id'],
                linkman_id : $_GET['linkman_id'],
            <?php }?>
            };
            // }else{
            //     new_order_info = {
            //         order_date: order_date,
            //         end_time: end_time, 
            //         update_time : time,
            //         /*price : $("#price").val(),*/
            //         amount : $("#amount").val(),
            //         /*cost : $("#cost").val(),*/
            //         /*service_charge_ratio : $("#fuwufei_input").val(),*/
            //         groom_name: $("#groom_name").val(),
            //         groom_phone: $("#groom_phone").val(),
            //         groom_wechat: $("#groom_wechat").val(),
            //         groom_qq: $("#groom_qq").val(),
            //         bride_name: $("#bride_name").val(),
            //         bride_phone: $("#bride_phone").val(),
            //         bride_wechat: $("#bride_wechat").val(),
            //         bride_qq: $("#bride_qq").val(),
            //         linkman_name : $("#linkman_name").val(),
            //         linkman_phone : $("#linkman_phone").val(),
            //         product_id : "<?php if (isset($_GET['product_id'])){echo $_GET['product_id'];}?>",
            //         remark : $("#remark").val(),
            //     };
            // }
            // console.log(new_order_info);
            if ($("#amount").val() =="" || $("#amount").val() ==0) {
                alert("请输入桌数");
            } else{
                $.post("<?php echo $this->createUrl("product/neworder");?>",new_order_info,function(retval){
                    alert(retval);
                    location.href = "<?php echo $this->createUrl('product/store');?>&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
                });
            };
            
        });

        //选择会议客户
        $("#meeting_company").on("click",function(){
            location.href = "<?php echo $this->createUrl("meeting/selectcustomer");?>&from=product_store&set_id=<?php echo $_GET['set_id']?>&product_id=<?php echo $_GET['product_id']?>&category=<?php echo $_GET['category']?>";
        });
    })
</script>
</body>
</html>
