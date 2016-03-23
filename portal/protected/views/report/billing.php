<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>报表</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
<link href="css/boss_chart.css" rel="stylesheet" type="text/css" />
</head>
<body class="bg_white">
<article>
    <div class="tool_bar mar_0">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">
            <select id="report_type" >
                <option type="0">周报</option>
                <option type="1">月报</option>
                <option type="2">季报</option>
                 <option type="3">年报</option>
            </select>
        </h2>
    </div>
    <div class="chart_info">
               
        <select id="hotel_type" class="hotel_type">
            <option hotel-id="1">大郊亭店</option>
            <option hotel-id="2">航天桥店</option>
        </select>
        <div>
            <p id = "left_arr" class = "time_arr"> < </p>
            <p id = "left_time" class="chart_time">yyyy-mm-dd</p>
            <p class = "time_arr">到</p>
            <p id = "right_time" class="chart_time">yyyy-mm-dd</p>
            <p id = "right_arr"  class = "time_arr"> > </p>
        </div>
        <p class="chart_desc">本周累计开单</p>
        <div class="flex">
            <div class="flex1" id="wedding">
                <p class="chart_label in_bl">婚礼：</p>
                <div class="in_bl">
                    <i class="chart_data" id="wedding_num"></i>
                    <i class="chart_data " id="wedding_rate"></i>
                </div>
            </div>
            <div class="flex1" id="meeting">
                <p class="chart_label in_bl" >会议：</p>
                <div class="in_bl">
                    <i class="chart_data" id="meeting_num"></i>
                    <i class="chart_data" id="meeting_rate"></i>
                </div>
            </div>
        </div>
    </div>
    <h4 class="chart_title">开单走势图</h4>
    <div class="chart_bar" id="chart"></div>
    <div class="btn">查看详细</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/charts/echarts.min.js"></script>
<!-- <script src="js/charts/line.js"></script> -->
<script src="js/common.js"></script>
<script>
$(function(){
    /************************************************
        分店名称及ID，PHP获取
        当天日期，PHP获取
    ************************************************/

    var width = $(document.body).width();
    var now_type = '0';
    var hotel_id = 1;//模拟数据
    $('#chart').css({'width': width, 'height': width*0.62});
    //初始化周报

    var curr_date = 0;//0为当前时间，负数代表前多久
    var left_time, right_time ;
    showDate('left');
    showPage(now_type,hotel_id);//hotel-id需php获得读取

    //选择周报、月报、年报等
    $("#report_type").on("change",function(){
        curr_date = 0 ;//日期规0；
        var report_type = $(this).find("option:selected").attr("type");//报告类型
        var select_hotel_id = $("#hotel_type").find("option:selected").attr("hotel-id");//选择酒店ID
        now_type = report_type;
        showDate('left');
        showPage(report_type,select_hotel_id);
    });
    //选择分店
    $("#hotel_type").on("change",function(){
        curr_date = 0 ;//日期规0；
        var select_hotel_id = $(this).find("option:selected").attr("hotel-id");
        var report_type = $("#report_type").find("option:selected").attr("type");
        hotel_id = select_hotel_id;
        showDate('left');
        showPage(report_type,select_hotel_id); 
    });

    //左日期按钮点击事件
    $("#left_arr").on("click",function(){
        curr_date--;
        showDate('left');
        showPage(now_type,hotel_id);
    });

    //右日期按钮点击事件
    $("#right_arr").on("click",function(){
        if(isCurrDate()) return;
        else{
            curr_date++;
            isCurrDate();
            showDate('right');
            showPage(now_type,hotel_id);            
        }

    });

    /*******************************************
        界面时间段显示
        传回是否为本周
    ********************************************/ 
    function isCurrDate(){
        if(curr_date == 0){
            $("#right_arr").addClass("grey_font_color");
            return true;
        }
        else{
            $("#right_arr").removeClass("grey_font_color");
            return false;           
        }
    }
    /*******************************************
        日期操作后的显示，初始化为left,direct=left是左，right是右
        现在函数暂时只支持周
    ********************************************/ 
    function showDate(direct){

        var temp_right_date=new Date();
        var temp_left_date;
        var important_chow = Date.parse(temp_right_date);//此为最右时间点时间戳，传给后台
        

        if(now_type == '0' && direct == 'left'){//如果是周报
            if( !isCurrDate() ){ //如果不是本周
                temp_right_date = new Date(parseInt(left_time.timestamp-1000));
                important_chow = left_time.timestamp-1000;
            }
            temp_left_date = $.util.addDate(temp_right_date,'d',(0-$.util.printDay(temp_right_date)+1));//获取本周星期一的日期
        }else if(now_type == '0' && direct == 'right'){
            temp_left_date = $.util.addDate(right_time.right_date,'d',1);
            if(curr_date != 0) {
                temp_right_date =  $.util.addDate(right_time.right_date,'d',7);
                important_chow =Date.parse(temp_right_date) +86400000-1000;
            }
        }else if(now_type == '1' && direct == 'left'){//如果是月报，左
            if( !isCurrDate() ){ //如果不是本月
                temp_right_date = new Date(parseInt(left_time.timestamp-1000));
                important_chow = left_time.timestamp-1000;
            }
            temp_left_date = $.util.addDate(temp_right_date,'d',(0-temp_right_date.getDate()+1));//获取本月1号的日期
        }else if(now_type == '1' && direct == 'right'){//如果是月报，右
            temp_left_date = $.util.addDate(right_time.right_date,'d',1);
            if(curr_date != 0) {
                temp_right_date =  $.util.addDate(right_time.right_date,'m',1);
                important_chow =Date.parse(temp_right_date) +86400000-1000;
            }
        }else if(now_type == '3' && direct == 'left'){//如果是年报，左
            if( !isCurrDate() ){ //如果不是本年
                temp_right_date = new Date(parseInt(left_time.timestamp-1000));
                important_chow = left_time.timestamp-1000;
            }
            temp_left_date = new Date(temp_right_date.getFullYear(),0,1);

        }else if(now_type == '3' && direct == 'right'){//如果是年报，右
            temp_left_date = $.util.addDate(right_time.right_date,'d',1);
            if(curr_date != 0) {
                temp_right_date =  new Date(temp_left_date.getFullYear(),11,31);
                important_chow =Date.parse(temp_right_date) +86400000-1000;
            }
        }


        right_time = {
            right_date:temp_right_date,
            timestamp: important_chow,//保存23:59:59的时间戳
            format_time: $.util.printDate(temp_right_date),//标准时间YY-DD-MM
            day : $.util.printDay(temp_right_date)//星期几
        };

        left_time = {
            left_date:temp_left_date,
            timestamp: Date.parse(temp_left_date),//00:00:00的时间戳
            format_time: $.util.printDate(temp_left_date),//标准时间YY-DD-MM
            day : $.util.printDay(temp_left_date)//星期几
        };
        console.log(left_time);
        console.log(right_time);

        $("#left_time").html(left_time.format_time);
        $("#right_time").html(right_time.format_time);
    }



    /*******************************************
        后台发起请求，数据渲染界面
        传入报表1类型type：'0'（周报），'1'（月报），'2'（季报），'3'（年报）
                  分店的ID
    ********************************************/   
    function showPage(type,hotel_id){
        //获取某个主题酒店的开单信息
        var temp_stamp = right_time.timestamp / 1000;
       $.getJSON('<?php echo $this->createUrl("report/info");?>',{hotel_id: hotel_id,chart1_type:type,show_date:temp_stamp,show_day:right_time.day
       },function(retval){
       // alert(retval);
           var ret = JSON.stringify(retval);
          // console.log(ret);
           if(retval.success){
            //retval格式，wedding{every_num:[],totle_num,rate,rate_type},meeting{every_num:[],totle_num,rate,rate_type}
          /*  var retval;
            switch (type){
                case '0':
                    retval = {
                        wedding:{
                            every_num:[1,2,3],
                            total_num:6,
                            rate:'15%',
                            rate_type:0//0增，1降
                        },
                        meeting:{
                            every_num:[0,0,1],
                            total_num:1,
                            rate:'15%',
                            rate_type:1//0增，1降
                        }
                    }
                    $(".chart_desc").html("本周累计开单"); 
                    break;
                case '1':
                    retval = {
                        wedding:{
                            every_num:[1,2,3,1,1,4,0,0,0],
                            total_num:12,
                            rate:'25%',
                            rate_type:0//0增，1降
                        },
                        meeting:{
                            every_num:[0,0,1,0,0,1,0,0,1],
                            total_num:3,
                            rate:'0%',
                            rate_type:0//0增，1降
                        }
                    }
                    $(".chart_desc").html("本月累计开单");
                    break;
                case '2':
                    $(".chart_desc").html("本季累计开单");
                    var retval = {
                        wedding:{
                            every_num:[0,2,3],
                            total_num:5,
                            rate:'35%',
                            rate_type:1//0增，1降
                        },
                        meeting:{
                            every_num:[0,0,1],
                            total_num:5,
                            rate:'30%',
                            rate_type:1//0增，1降
                        }
                    }
                    break;
                case '3':
                    var retval = {
                        wedding:{
                            every_num:[1,2,3,1,1,4,0,0,0,10,11,8],
                            total_num:41,
                            rate:'45%',
                            rate_type:0//0增，1降
                        },
                        meeting:{
                            every_num:[0,0,1,2,3,1,1,4,0,0,0,10],
                            total_num:22,
                            rate:'40%',
                            rate_type:0//0增，1降
                        }
                    }
                   $(".chart_desc").html("本年累计开单");
                   break;
                }
                */
               console.log(ret);
                drawChart1(type,retval);                
          // }else{
          //   alert('太累了，歇一歇，一秒后再试试！');
          //   return false;
           }
         });

    }

    /*******************************************
        渲染界面总数、比例、及图表，
        传入type：0（周报），1（月报），2（季报），3（年报），以及后台提供的json
    ********************************************/   
    function drawChart1(type,data){
        //1、计算婚礼、会议总数
        /*var this_total_wedding = 0, last_total_wedding = 0;
        var this_total_meeting = 0, last_total_meeting = 0;
        var wedding_rate = 0, meeting_rate = 0;

        for(i=0; i<data.wedding.this_num.length; i++){
            this_total_wedding += data.wedding.this_num[i];
            this_total_meeting += data.meeting.this_num[i];
        }

        for(i=0; i<data.wedding.last_num.length; i++){
            last_total_wedding += data.wedding.last_num[i];
            last_total_meeting += data.meeting.last_num[i];
        }

        $("#wedding_num").html(this_total_wedding+"单");
        $("#meeting_num").html(this_total_meeting+"单");*/

        $("#wedding_num").html(data.wedding.total_num+"单");
        $("#meeting_num").html(data.meeting.total_num+"单");


        //2、计算婚礼及会议同比
        /*if(last_total_wedding != 0 && this_total_wedding >= last_total_wedding){
            wedding_rate = ((this_total_wedding - last_total_wedding) * 100 /last_total_wedding).toFixed(0);
            $("#wedding_rate").addClass('t_red').html(wedding_rate+"%");
        }else if(last_total_wedding != 0 && this_total_wedding < last_total_wedding){
            wedding_rate = ((- this_total_wedding + last_total_wedding) * 100 /last_total_wedding).toFixed(0);
            $("#wedding_rate").addClass('t_gray').html(wedding_rate+"%");
        }else{
            wedding_rate = this_total_wedding * 100;
            $("#wedding_rate").addClass('t_red').html(wedding_rate+"%");
        }

        if(last_total_meeting != 0 && this_total_meeting >= last_total_meeting){
            meeting_rate = ((this_total_meeting - last_total_meeting) * 100 /last_total_meeting).toFixed(0);
            $("#meeting_rate").addClass('t_red').html(meeting_rate+"%");
        }else if(last_total_meeting != 0 && this_total_meeting < last_total_meeting){
            meeting_rate = ((- this_total_meeting + last_total_meeting) * 100 /last_total_meeting).toFixed(0);
            $("#meeting_rate").addClass('t_gray').html(meeting_rate+"%");
        }else{
            meeting_rate = this_total_meeting * 100;
            $("#meeting_rate").addClass('t_red').html(meeting_rate+"%");
        }*/
        var wedding_rate_color = 't_red', meeting_rate_color = 't_red';
        console.log(data.wedding.rate_type+','+data.meeting.rate_type);
        if(data.wedding.rate_type == 1) wedding_rate_color = 't_gray';//如果同比降低了，变成灰色
        if(data.meeting.rate_type == 1) meeting_rate_color = 't_gray';
        console.log(wedding_rate_color+','+meeting_rate_color);
        $("#wedding_rate").removeClass('t_red t_gray').addClass(wedding_rate_color).html(data.wedding.rate);
        $("#meeting_rate").removeClass('t_red t_gray').addClass(meeting_rate_color).html(data.meeting.rate);


        //横坐标显示内容
        var date = new Date();
        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var xAxis_data = new Array();
        switch(type){
            case '0'://周报
                xAxis_data = ['周一','周二','周三','周四','周五','周六','周日'];
                break;
            case '1'://月报
                if(month == 1 || month == 3 || month == 5 || month == 7 || month == 8 || month == 10 || month == 12 ){
                    xAxis_data = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'];
                }else if(month == 4 || month == 6 || month == 9 || month == 11 ){
                    xAxis_data = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30'];
                }else if(month == 2 && year%4 == 0){
                    xAxis_data = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29'];
                }else{
                    xAxis_data = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28'];
                }
                break;
            case '2'://季报
                if(month == 1 || month == 2 || month == 3){
                    xAxis_data = ['1月','2月','3月'];                   
                }else if(month == 4 || month == 5 || month == 6){
                    xAxis_data = ['4月','5月','6月'];
                }else if(month == 7 || month == 8 || month == 9){
                    xAxis_data = ['7月','8月','9月'];
                }else {
                    xAxis_data = ['10月','11月','12月'];
                }
                break;
            case '3'://年报
                xAxis_data = ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'];
                break;
        }
        //console.log(data);
        //构造echarts折线图并显示
        //基于准备好的dom，初始化echarts图表
        var myChart = echarts.init(document.getElementById('chart')); 
        var option = {
            grid : {  
                x : 40,
                y : 40,
                x2: 40,
                y2: 40
            },
            legend: {
                data:['婚礼','会议'],
                x: 'right'
            },
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : xAxis_data
                }
            ],
            yAxis : [
                {
                    type : 'value',
                }
            ],
            series : [
                {
                    name:'婚礼',
                    type:'line',
                    data:data.wedding.every_num,
                },
                {
                    name:'会议',
                    type:'line',
                    data:data.meeting.every_num,
                }
            ]
        };
        // 为echarts对象加载数据 
        myChart.setOption(option);
       // console.log("huiyi"+data.wedding.every_num);
    }


});
</script>
</body>
</html>
