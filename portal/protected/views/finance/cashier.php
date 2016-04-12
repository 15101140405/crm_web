<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>收取现金</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
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
    #time input, select {
        width: 100%;
        padding: 5px;
        border: 1px solid #aaa;
        height: 48px;
        font-size: 1.4rem;
        padding: 12px;
        background: #eee;
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
    <div class="tool_bar fixed">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">收取现金</h2>
    </div>
    <div style='position:relative;bottom:60px;top:60px;' id="top" paymentId="<?php echo $_GET['paymentId']?>">
        <div class="select_ulist_module" id="type">
            <ul class="select_ulist" id="type_ul">
                <li class="select_ulist_item round_select round_select_selected" type-value="0">定金</li>
                <li class="select_ulist_item round_select" type-value="1">中期款</li>
                <li class="select_ulist_item round_select" type-value="2">尾款</li>
            </ul>
        </div>
        <div class="ulist_module">
            <ul class="ulist">
                <li class="ulist_item flex" id="2">
                    <span class='t_green'>收款金额</span>
                    <div class="flex1">
                        <input id="cashier" class="align_r" type="text" placeholder="输入金额"/>
                    </div>
                    <i class="mar_l10 t_green">元</i>
                </li>
            </ul>
        </div>
        <div class="select_ulist_module" id="way">
            <ul class="select_ulist" id="way_ul">
                <li class="select_ulist_item round_select round_select_selected" way-value="0">现金</li>
                <li class="select_ulist_item round_select" way-value="1">公户</li>
                <li class="select_ulist_item round_select" way-value="2">支票</li>
            </ul>
        </div>
        <div class="demos" id="time" style="margin-top:15px;margin-bottom:15px">
            <p for="appDateTime" style="color:#00b90c;font-size:1.4rem;padding-left:12px;background-color:#fff;line-height:48px;">收款时间</p>
            <input value="2015-05-01 15:42:02" class="" readonly="readonly" name="appDateTime" id="appDateTime" type="text">
        </div>
        <div class="text_bar" style="background: #fff;">
            <p for="appDateTime" style="font-size:1.4rem;background-color:#fff;line-height:48px;">备注</p>
            <textarea maxlength="70" placeholder="请填写记录内容" id="remark"></textarea>
        </div>
    </div>
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="insert">确认收款</div>
        <div class="r_btn" id="update">确认</div>
        <div class="r_btn" id="del" style="background-color:red;">删除</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //页面初始化
        if ("<?php echo $_GET['type']?>" == "edit") {
            /*alert('edit');*/
            $("#insert").remove();
            $("#type li").removeClass("round_select_selected");
            $("[type-value='<?php echo $data['type']?>']").addClass("round_select_selected");
            $("#cashier").val("<?php echo $data['money'];?>")
            $("#way li").removeClass("round_select_selected");
            $("[way-value='<?php echo $data['way']?>']").addClass("round_select_selected");
            $("#remark").val("<?php echo $data['remarks']?>");
            $("appDateTime").val("<?php echo $data['time']?>");
            //此处用php从后端取数
        } else if ("<?php echo $_GET['type']?>" == "new") {
            /*alert('new');*/
            $("#del").remove();
            $("#update").remove();
        };

        //点击新增
        $("#insert").on("click",function(){
            data = {payment:$("#cashier").attr("value"),payment_time:$('#appDateTime').val(),payment_way:$('#way_ul .round_select_selected').attr('way-value'),payment_type:$('#type_ul .round_select_selected').attr('type-value'),order_id:<?php echo $_GET["order_id"]?>,remarks:$("#remark").val()};
            console.log(data);
            $.post('<?php echo $this->createUrl("finance/indexdata");?>',data,function(retval){
                location.href='<?php echo $this->createUrl("finance/cashierlist");?>&order_id=<?php echo $_GET['order_id'];?>&from=<?php echo $_GET['from']?>';
            });
        });

        //点击编辑
        $("#update").on("click",function(){

            data = {payment:$("#cashier").attr("value"),payment_time:$('#appDateTime').val(),payment_way:$('#way_ul .round_select_selected').attr('way-value'),payment_type:$('#type_ul .round_select_selected').attr('type-value'),paymentId:"<?php echo $_GET["paymentId"]?>",remarks:$("#remark").val()};
            console.log(data);
            $.post('<?php echo $this->createUrl("finance/orderpaymentupdate");?>',data,function(retval){
                location.href='<?php echo $this->createUrl("finance/cashierlist");?>&order_id=<?php echo $_GET['order_id'];?>&from=<?php echo $_GET['from']?>';
            });
        });

        //点击删除
        $("#del").on("click",function(){
            data = {paymentId:"<?php echo $_GET["paymentId"]?>"};
            console.log(data);
            $.post('<?php echo $this->createUrl("finance/orderpaymentdel");?>',data,function(retval){
                location.href='<?php echo $this->createUrl("finance/cashierlist");?>&order_id=<?php echo $_GET['order_id'];?>&from=<?php echo $_GET['from']?>';
            });
        });

        //选择收款方式
        $("#way_ul li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $("#way_ul li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            }           
        });

        //选择收款类型
        $("#type_ul li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $("#type_ul li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            }           
        });


        //时间插件
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
    })   
</script>
</body>
</html>
