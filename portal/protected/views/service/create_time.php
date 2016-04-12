<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>创建订单</title>
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
        <div class="l_btn hid" data-icon="&#xe679;"></div>
        <h2 class="page_title">创建婚礼订单</h2>
        <div class="r_btn">确定</div>
    </div>
    <!-- <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">预计桌数</span>
                <div class="int_bar mar_r10">
                    <input class="align_r" id="expect_table" type="text" placeholder="预计桌数"/>
                </div>
                <span class="float_r t_green">桌</span>
            </li>
        </ul>
    </div> -->
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
    <div class="select_ulist_module" id="hotel">
            <ul class="select_ulist" id="hotel_ul">
                <?php foreach ($hotel as $key => $value) { ?>
                <li class="select_ulist_item round_select" hotel-id="<?php echo $value['id']?>"><?php echo $value['name']?></li>
                <?php }?>
            </ul>
    </div>
    
</article>

<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
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



        

        var order_date = localStorage.getItem("new_order_day");//获得所选择的时间
        $('.module_title').html('时间［'+order_date+']');

        //返回按钮
        $(".l_btn").on("click", function () {
            localStorage.removeClass("order_type");
            location.href = "<?php echo $this->createUrl("order/selectType");?>";
        });
        //确定按钮
        $(".r_btn").on("click", function () {

            //console.log($(".select_ulist .select_selected").index());
            //将此婚礼信息提交给后台
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
                order_type: 2,
                order_name: '新订单',
                order_time: 0, 
                end_time: end_time, 
                update_time : time,
                hotel_id : $(".round_select_selected").attr("hotel-id"),
            };
            

            $.post('<?php echo $this->createUrl("plan/wedinsert");?>',new_order_info,function(retval){
                /*if(retval.success){*/
                location.href = "<?php echo $this->createUrl("plan/customername");?>&order_id="+retval;
                /*}else{*/
            //  alert("BI偷了个懒！");
            // }
            });
            
        })


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
