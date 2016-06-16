<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>会议－基本信息</title>
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
        body {
            padding: 0;
            margin: 0;
            font-family: arial, verdana, sans-serif;
            font-size: 12px;
            background: #ddd;
        }
        input, select {
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
        .header {
            border: 1px solid #333;
            background: #111;
            color: white;
            font-weight: bold;
            text-shadow: 0 -1px 1px black;
            background-image: linear-gradient(#3C3C3C,#111);
            background-image: -webkit-gradient(linear,left top,left bottom,from(#3C3C3C),to(#111));
            background-image: -moz-linear-gradient(#3C3C3C,#111);
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
        <h2 class="page_title">会议</h2>
        <div class="r_btn" data-icon="&#xe6a3;"></div>
    </div>
    <div class="content">
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
    <!-- <div class="select_ulist_module" id="hotel">
            <ul class="select_ulist" id="hotel_ul">
                <?php foreach ($hotel as $key => $value) { ?>
                <li class="select_ulist_item round_select" hotel-id="<?php echo $value['id']?>"><?php echo $value['name']?></li>
                <?php }?>
            </ul>
    </div> -->
    <!-- <div class="select_ulist_module">
        <h4 class="module_title">会议时间</h4>
        <ul class="select_ulist">
            <li class="select_ulist_item select select_selected">上午</li>
            <li class="select_ulist_item select">下午</li>
            <li class="select_ulist_item select">全天</li>
        </ul>
    </div> -->
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>

<script>
    $(function () {
        if("<?php echo $_GET['from']?>" == "detailinfo"){
            $(".l_btn").remove();
            $(".r_btn").html("确定");
            $(".r_btn").attr("data-icon","");
            $("#appDate").val("<?php echo $order_date?>");
            $("#appTime").val("<?php echo $order_time?>");
            $("#appTime1").val("<?php echo $end_time?>");
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



        
        //确定按钮
        if("<?php echo $_GET['from']?>" == "selecttype" || "<?php echo $_GET['from']?>" == "meeting_feast" || "<?php echo $_GET['from']?>" == "set"){
            $(".r_btn").on("click", function () {
                //传给后台参数
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

                var new_order_info = {
                    order_date: order_date,
                    order_type: 1,
                    order_name: '新订单',
                    order_time: 0, 
                    end_time: end_time, 
                    update_time : time,
                    hotel_id : "<?php echo $_SESSION['staff_hotel_id']?>",
                    set_id : "<?php if (isset($_GET['set_id'])){echo $_GET['set_id'];}?>",
                    product_id : "<?php if (isset($_GET['product_id'])){echo $_GET['product_id'];}?>",
                };
                $.post('<?php echo $this->createUrl("meeting/meetinginsert");?>',new_order_info,function(retval){
                        /*if(retval.success){*/
                            location.href = "<?php echo $this->createUrl("meeting/selectcustomer");?>&set_id=<?php if (isset($_GET['set_id'])){echo $_GET['set_id'];}?>&product_id=<?php if (isset($_GET['product_id'])){echo $_GET['product_id'];}?>&from=selecttime&company_id=&order_id="+retval;
                        /*}else{*/
                    //  alert("BI偷了个懒！");
                    // }
                    });
            });
        }else{
            $(".r_btn").on("click", function () {
                data={
                    order_id : "<?php echo $_GET['order_id']?>",
                    order_date : $("#appDate").val()+" "+$("#appTime").val()+":00",
                    end_time : $("#appDate").val()+" "+$("#appTime1").val()+":00",
                };
                $.post('<?php echo $this->createUrl("meeting/updatetime");?>',data,function(retval){
                        /*if(retval.success){*/
                            location.href = "<?php echo $this->createUrl("meeting/detailinfo");?>&order_id=<?php echo $_GET['order_id']?>";
                        /*}else{*/
                    //  alert("BI偷了个懒！");
                    // }
                    });
            });
        }

        //返回按钮
        $(".l_btn").on("click", function () {
            //清空已选时间
            location.href = "<?php echo $this->createUrl("order/selecttype");?>";
        });

        //时间勾选
        $(".select_ulist li").on("click", function () {
            $(".select_ulist li").removeClass("select_selected");
            $(this).addClass("select_selected");
        });

        //选择门店
        $("#hotel_ul li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $("#hotel_ul li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            }           
        });

    })
</script>
</body>
</html>