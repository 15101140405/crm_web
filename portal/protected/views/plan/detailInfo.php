<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>详细资料</title>
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
        .input {
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
        <h2 class="page_title">详细资料</h2>
        <div id="edit" class="r_btn">修改</div>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">婚礼信息</h4>
        <ul class="int_ulist">
            <!-- <li class="int_ulist_item">
                <span class="label">预计桌数</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="number" disabled="disabled" value="" placeholder=""/>
                </div>
            </li> -->
            <li class="int_ulist_item">
                <div class="demos">
                    <label for="appDate">婚期</label>
                    <input value="<?php echo $arr['date']?>" class="input" disabled="disabled" placeholder="请选择日期" readonly="readonly" name="appDate" id="appDate" type="text">
                </div>

                <div class="demos">
                    <label for="appTime">开始时间</label>
                    <input value="<?php echo $arr['start_time']?>" disabled="disabled" class="input" placeholder="请选择开始时间" readonly="readonly" name="appTime_start" id="appTime" type="text">
                </div>

                <div class="demos">
                    <label for="appTime">结束时间</label>
                    <input value="<?php echo $arr['end_time']?>" disabled="disabled" class="input" placeholder="请选择结束时间" readonly="readonly" name="appTime_end" id="appTime1" type="text">
                </div>
            </div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">新郎信息</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">新郎姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="groom_name" disabled="disabled" value="<?php echo $arr['boy_name'];?>" placeholder="<?php echo $arr['boy_name'];?>"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="groom_phone" disabled="disabled" value="<?php echo $arr['boy_tele'];?>" placeholder="<?php echo $arr['boy_tele'];?>"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">微信</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="groom_wechat" disabled="disabled" value="<?php echo $arr['boy_wxid'];?>" placeholder="<?php echo $arr['boy_wxid'];?>"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">QQ</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="groom_qq" disabled="disabled" value="<?php echo $arr['boy_qq'];?>" placeholder="<?php echo $arr['boy_qq'];?>"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">新娘信息</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">新娘姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="bride_name" disabled="disabled" value="<?php echo $arr['girl_name'];?>" placeholder="<?php echo $arr['girl_name'];?>"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="bride_phone" disabled="disabled" value="<?php echo $arr['girl_tele'];?>" placeholder="<?php echo $arr['girl_tele'];?>"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">微信</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="bride_wechat" disabled="disabled" value="<?php echo $arr['girl_wxid'];?>" placeholder="<?php echo $arr['girl_wxid'];?>"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">QQ</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="bride_qq" disabled="disabled" value="<?php echo $arr['girl_qq'];?>" placeholder="<?php echo $arr['girl_qq'];?>"/>
                </div>
            </li>
        </ul>
    </div>
    <!-- @$ 增加联系人信息，到此div结束-->
    <div class="int_ulist_module" style="margin-bottom: 70px;">
        <h4 class="module_title">联系人信息</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">联系人姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="link_name" disabled="disabled" value="<?php echo $arr['link_name'];?>" placeholder="<?php echo $arr['girl_name'];?>"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" id="link_phone" disabled="disabled" value="<?php echo $arr['link_phone'];?>" placeholder="<?php echo $arr['girl_tele'];?>"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="bottom_fixed_bar hid" id='bottom'>
        <div id='save' class="r_btn" id="insert">提交订单</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function(){
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



        //初始化
        $("#bottom").removeClass("hid");
        $("input").removeAttr("disabled");
        $("input").addClass("edit_color");
        $("#edit").remove();
        $("#save").on("click",function(){
            var detail_info = get_info();
            $.post("<?php echo $this->createUrl('plan/updatedetail');?>",detail_info,function(data){
                location.href = "<?php echo $this->createUrl("design/bill");?>&from=&order_id=<?php echo $_GET['order_id']?>";
            });
        })
        

        /*//返回
        var from = $.util.param("from");

        $("div.l_btn").on("click",function(){
            if(from == "plan"){
                location.href = "<?php echo $this->createUrl("plan/detail", array());?>";
            }
            else
            {
                location.href = "<?php echo $this->createUrl("design/detail");?>";
            }
        })*/

        //获取界面上所有表单的数据
        function get_info(){

            var detail_info = {
                order_id : <?php echo $_GET['order_id'];?>,
                order_date : $("#appDate").val()+" "+$("#appTime").val()+":00",
                end_time : $("#appDate").val()+" "+$("#appTime1").val()+":00",
                groom_name : $("#groom_name").val(),
                groom_phone : $("#groom_phone").val(),
                groom_wechat : $("#groom_wechat").val(),
                groom_qq : $("#groom_qq").val(),
                bride_name : $("#bride_name").val(),
                bride_phone : $("#bride_phone").val(),
                bride_wechat : $("#bride_wechat").val(),
                bride_qq : $("#bride_qq").val(),
                link_name : $("#link_name").val(),
                link_phone : $("#link_phone").val(),
            };
            return detail_info;
        }


    })
</script>
</body>
</html>
