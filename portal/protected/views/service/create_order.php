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
        .ulist input{
            height: 3.5rem;
            width: 200px;
            line-height: 3.5rem;
            float: right;
            margin-top: 12px;
        }
        .ulist li{
            height: 5.5rem;
            line-height: 5.5rem;
        }
        .select_ulist input{
            height: 3.5rem;
            width: 200px;
            line-height: 3.5rem;
            float: right;
            margin-top: 12px;
        }
        .select_ulist li{
            height: 5.5rem;
            line-height: 5.5rem;
        }
    </style>
</head>
<body>
<article>
    <div class="tool_bar">
        <!-- <div class="l_btn hid" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">创建婚礼订单</h2>
        <!-- <div class="r_btn">确定</div> -->
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
    </div>
    <div class="ulist_module pad_b40" style="margin-top:20px;">
        <ul class="ulist">
            <li class="ulist_item " id="detailinfo">
                <span class="big_font">基本信息</span>
            </li>
            <li class="ulist_item flex">
                客户姓名
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="order_name" placeholder="例如：陈磊&王晓媛"/>
                </div>
            </li>
            <li class="ulist_item flex">
                地点
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="place" placeholder="请输入地点"/>
                </div>
            </li>
            <li class="ulist_item flex">
                联系人
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="linkman" placeholder="例如：乔项"/>
                </div>
            </li>
            <li class="ulist_item flex">
                联系人电话
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="linkman_phone" placeholder="请输入联系人电话"/>
                </div>
            </li>
        </ul>
    </div>
    
    <div class="select_ulist_module">
        <ul class="select_ulist" id="price_ul">
            <li class="ulist_item " id="detailinfo">
                <span class="big_font">报价</span>
            </li>
            <li class="ulist_item flex">
                报价
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="price" />
                </div>
            </li>     
        </ul>
    </div>

    <div class="ulist_module pad_b50">
        <ul class="ulist " >
            <li class="ulist_item">备注</li>
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="请输入备注" id="remark"></textarea>
                </div>
            </li> 
        </ul>
    </div>
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="insert">创建订单</div>
        <div class="r_btn" id="update">确定</div>
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

        //页面初始化
        if("<?php echo $_GET['service_order_id']?>" != ""){
            var order_date = "<?php echo $order['order_date']?>";
            var t1 = order_date.split(' ');
            $("#appDate").val(t1[0]);
            $("#appTime").val(t1[1]);
            $("#order_name").val("<?php echo $order['order_name']?>");
            $("#place").val("<?php echo $order['order_place']?>");
            $("#linkman").val("<?php echo $order['linkman_name']?>");
            $("#linkman_phone").val("<?php echo $order['linkman_phone']?>");
            $("#price").val("<?php echo $order['price']?>");
            $("#remark").val("<?php echo $order['remarks']?>");
            $("#insert").remove();
            $(".page_title").html("订单编辑");
        }else{
            $("#update").remove();
        }

        //新增
        $("#insert").on("click", function () {
            
            new_order_info = get_info();

            $.post('<?php echo $this->createUrl("service/insert_order");?>',new_order_info,function(retval){
                location.href = "<?php echo $this->createUrl("service/my");?>";
            });
            
        })

        //编辑
        $("#update").on("click", function () {
            
            new_order_info = get_info();
            alert(new_order_info.service_order_id);
            $.post('<?php echo $this->createUrl("service/update_order");?>',new_order_info,function(retval){
                location.href = "<?php echo $this->createUrl("service/my");?>";
            });
            
        })

        function get_info()
        {
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

            var new_order_info = {
                service_order_id: "<?php echo $_GET['service_order_id']?>",
                order_date: order_date,
                order_name: $("#order_name").val(),
                place: $("#place").val(),
                linkman: $("#linkman").val(),
                linkman_phone: $("#linkman_phone").val(),
                price: $("#price").val(),
                update_time : time,
                remarks: $("#remark").val(),
            };
            return new_order_info;
        }

    })
</script>

</body>
</html>
