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
        <h2 class="page_title">跟进记录</h2>
    </div>
    <div class="select_ulist_module" id="way" followId="<?php echo $_GET['followId']?>">
        <ul class="select_ulist">
            <li class="ulist_item">记录内容</li> 
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="请填写记录内容" id="remark"></textarea>
                </div>
            </li> 
        </ul>
        <ul class="select_ulist" id="type" >
            
            <li class="select_ulist_item round_select round_select_selected" value="0">进店面谈</li>
            <li class="select_ulist_item round_select" value="1">打电话</li>
            <li class="select_ulist_item round_select" value="2">发微信</li>
        </ul>
        <div class="demos" id="time">
            <label for="appDateTime">收款时间</label>
            <input value="2015-05-01 15:42:02" class="" readonly="readonly" name="appDateTime" id="appDateTime" type="text">
        </div>
    </div>
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="insert">确定</div>
        <div class="r_btn" id="update">确定</div>
        <div class="r_btn" id="del" style="background-color:red;">删除</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        
        //页面初始化
        if ("<?php echo $_GET['type']?>" == "edit") {
            $("#insert").remove();
            $("#type li").removeClass("round_select_selected");
            $("[value='<?php echo $data['type']?>']").addClass("round_select_selected");
            $("#remark").val("<?php echo $data['remarks']?>");
            $("appDateTime").val("<?php echo $data['time']?>");
            //此处用php从后端取数
        } else if ("<?php echo $_GET['type']?>" == "new") {
            $("#del").remove();
            $("#update").remove();
        };
        

        //点击新增
        $("#insert").on("click",function(){

            var data = {
                'remarks' : $("#remark").val(),
                'type' : $(".round_select_selected").attr("value"),
                'order_id' : "<?php echo $_GET['order_id']?>",
                'time' : $("#appDateTime").val(),
                'staff_id' :"<?php echo $_SESSION['userid'];?>",
            }
            console.log(data);
            $.post("<?php echo $this->createUrl('order/followinsert');?>",data,function(){

                alert("添加成功");
                location.href="<?php echo $this->createUrl('order/orderinfofollow');?>&order_id=<?php echo $_GET["order_id"]?>";
            })
        });
        //点击编辑
        $("#update").on("click",function(){
            
            var data = {
                'remarks' : $("#remark").val(),
                'type' : $(".round_select_selected").attr("value"),
                'followId' : $("#way").attr("followId"),
                'time' : $("#appDateTime").val()
            };

            $.post("<?php echo $this->createUrl('order/followupdate');?>",data,function(){
                alert("修改成功");
                location.href="<?php echo $this->createUrl('order/orderinfofollow');?>&order_id=<?php echo $_GET["order_id"]?>";
            })
        });
        //点击删除
        $("#del").on("click",function(){

            var data = {'followId' : $('#way').attr('followId') };

            $.post("<?php echo $this->createUrl('order/followdel');?>",data,function(){
                alert("删除成功");
                location.href="<?php echo $this->createUrl('order/orderinfofollow');?>&order_id=<?php echo $_GET["order_id"]?>";
            })
        });

            

        //选择跟单方式
        $("#type li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $("#type li").removeClass("round_select_selected");
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
