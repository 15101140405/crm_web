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
<body>
<article>
    <div class="tool_bar mar_0">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">
            <select id="sale_type">
                <option type="1">本月销售情况</option>
                <option type="2">本季销售情况</option>
                <option type="3">本年销售情况</option>
            </select>
        </h2>
    </div>
    <div class="chart_info">
        <select id="hotel_type" class="hotel_type">
                <option  hotel-id="1">大郊亭店</option>
                <option  hotel-id="2">航天桥店</option>
        </select>
        <p class="chart_time">yyyy-mm-dd</p>
        <p class="chart_desc">本月累计流水</p>
        <div class="flex">
            <div class="flex1">
                <p class="chart_label in_bl">现金流入</p>
                <p class="chart_data t_red in_bl" id="income_money">32万元</p>
            </div>
            <div class="flex1">
                <p class="chart_label in_bl">应收账款</p>
                <p class="chart_data t_gray in_bl" id="debt_money">18万元</p>
            </div>
        </div>
    </div>
    <div>
        <div class="chart_bar l_chart" id="chart1"></div>
        <div class="r_chart">
            <div class="chart_bar" id="chart2"></div>
            <div class="chart_bar" id="chart3"></div>
        </div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/charts/echarts.min.js"></script>
<script>
$(function(){
    //分店名称及ID，PHP获取
    //当天日期，PHP获取

    var width = $(document.body).width();
    $('#chart1').css({'width': width/1.8 , 'height': width*0.72});
    $('#chart2').css({'width': width/2.5, 'height': width*0.4});
    $('#chart3').css({'width': width/2.5, 'height': width*0.4});

    // 基于准备好的dom，初始化echarts图表
    var myChart1 = echarts.init(document.getElementById('chart1'));
    var myChart2 = echarts.init(document.getElementById('chart2'));
    var myChart3 = echarts.init(document.getElementById('chart3'));

    showPage('1',1);//hotel-id需php获得读取

    //选择月、季、年等
    $("#sale_type").on("change",function(){
        var report_type = $(this).find("option:selected").attr("type");//报告类型
        var select_hotel_id = $("#hotel_type").find("option:selected").attr("hotel-id")//选择酒店ID
        showPage(report_type,select_hotel_id);
    });

    //选择分店
    $("#hotel_type").on("change",function(){
        var select_hotel_id = $(this).find("option:selected").attr("hotel-id");
        var report_type = $("#report_type").find("option:selected").attr("type");
        showPage(report_type,select_hotel_id); 
    });

    /*******************************************
        渲染界面总数、比例、及图表，
        传入type：1（月），2（季），3（年）
            hotel_id
    ********************************************/   
    function showPage(type,hotel_id){
        // $.getJSON('#',{hotel_id: hotel_id,chart3_type:type},function(retval){
        //   if(retval.success){
            //retval格式（保留整数）， 现金流入income_money(万元）,应收账款debt_money(万元）,目标销售额target_sale，实际销售额actual_sale
            
            var retval = {
                income_money:32,
                debt_money:18,
                target_sale:350,
                actual_sale:150
            }
            switch (type){
                case '2':
                    retval = {income_money:100,debt_money:38,target_sale:1000,actual_sale:600};//此行本不该有
                    $(".chart_desc").html("本季累计流水");
                    break;
                case '3':
                    retval = {income_money:400,debt_money:38,target_sale:3200,actual_sale:1400};//此行本不该有
                    $(".chart_desc").html("本年累计流水");
                    break;
                default:                   
                    $(".chart_desc").html("本月累计流水");
                    break;
            }

            $("#income_money").html(retval.income_money+"万元");
            $("#debt_money").html(retval.debt_money+"万元");


            drawChart3(retval);      
          // }else{
          //   alert('太累了，歇一歇，一秒后再试试！');
          //   return false;
          // }
        // });

    }

    function drawChart3(data){
        //3个仪表盘个性数据调整
        var my_chart_info=[{
            name:'销售额',
            max: data.target_sale, //****************************目标销售额,需从数据库读取
            axisLineStyle:{
                color: [[0.5, '#d9434b'],[0.8, '#48b'],[1.0, '#00b90c']], 
                width: 15
            },
            splitLineLength:'15%',
            axisLabelShow:true,
            titleFontSize:15,
            detail:{
                width:50,height:30,fontSize:24
            },
            data:[{value: data.actual_sale, name: '销售额'}]//****************************实际销售额,需从数据库读取
        },{
            name:'毛利润',
            max: data.target_sale*0.5, //***************************目标毛利润=目标销售额*0.5
            axisLineStyle:{
                color: [[0.2, '#bdd7ee'],[0.4, '#9dc3e6'],[0.8, '#48b'],[1.0, '#1f4e79']],  
                width: 10
            },
            splitLineLength:'10%',
            axisLabelShow:false,
            titleFontSize:12,
            detail:{
                width:40,height:20,fontSize:20
            },
            data:[{value: data.actual_sale*0.5, name: '毛利润'}]//***************************实际毛利润=实际销售额*0.5
        },{
            name:'纯利润',
            max: data.target_sale*0.2, //***************************目标毛利润=目标销售额*0.5
            axisLineStyle:{
                color: [[0.2, '#CDE7CE'],[0.4, '#8FCE9B'],[0.8, '#009900'],[1.0, '#006600']],
                width: 10
            },
            splitLineLength:'10%',
            axisLabelShow:false,
            titleFontSize:12,
            detail:{
                width:40,height:20,fontSize:20
            },
            data:[{value: data.actual_sale*0.2, name: '纯利润'}]//***************************实际毛利润=实际销售额*0.5
        }];

        var option = new Array();

        for(var i=0; i<3; i++){
            option[i] = {
                tooltip : {
                    formatter: "{a} <br/>{b} : {c}万元"
                },
                series : [
                {
                    name: my_chart_info[i].name,
                    radius : [0, '95%'],
                    type:'gauge',
                    startAngle: 140,
                    endAngle : -140,
                    min: 0,                     // 最小值
                    max: my_chart_info[i].max,                   // 最大值,此处需要修改
                    //precision: 0,               // 小数精度，默认为0，无小数点
                    splitNumber: 10,             // 分割段数，默认为5
                    axisLine: {            // 坐标轴线
                        show: true,        // 默认显示，属性show控制显示与否
                        lineStyle: my_chart_info[i].axisLineStyle,      // 属性lineStyle控制线条样式
                    },
                    axisTick: {            // 坐标轴小标记
                        show: true,        // 属性show控制显示与否，默认不显示
                        splitNumber: 5,    // 每份split细分多少段
                        length :'8%',         // 属性length控制线长
                        lineStyle: {       // 属性lineStyle控制线条样式
                            color: '#eee',
                            //width: 1,
                            type: 'solid'
                        }
                    },
                    axisLabel: {           // 坐标轴文本标签，详见axis.axisLabel
                        show: my_chart_info[i].axisLabelShow,
                        formatter: function(v){
                            switch (v+''){
                                case '20': return '低';
                                case '60': return '中';
                                case '90': return '高';
                                default: return '';
                            }
                        },
                        textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                            color: '#333',
                            fontSize: '1rem',
                            fontFamily:'Microsoft Yahei'
                        }
                    },
                    splitLine: {           // 分隔线
                        show: true,        // 默认显示，属性show控制显示与否
                        length :'15%',         // 属性length控制线长
                        lineStyle: {       // 属性lineStyle（详见lineStyle）控制线条样式
                            color: '#eee',
                            //width: 2,
                            type: 'solid'
                        }
                    },
                    pointer : {
                        length : '80%',
                        width : 8,
                        color : 'auto'
                    },
                    title : {
                        show : true,
                        offsetCenter: ['-65%', -10],       // x, y，单位px
                        textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                            color: '#333',
                            fontSize : my_chart_info[i].titleFontSize ,
                            fontFamily:'Microsoft Yahei'
                        }
                    },
                    detail : {
                        show : true,
                        backgroundColor: 'rgba(0,0,0,0)',
                        borderWidth: 0,
                        borderColor: '#ccc',
                        width: my_chart_info[i].detail.width,
                        height: my_chart_info[i].detail.height,
                        offsetCenter: ['-70%', 2],       // x, y，单位px
                        formatter:'{value}万',
                        textStyle: {       // 其余属性默认使用全局文本样式，详见TEXTSTYLE
                            fontSize : my_chart_info[i].detail.fontSize
                        }
                    },
                    data:my_chart_info[i].data
                }]
            }
        }

        // 为echarts对象加载数据 
        myChart1.setOption(option[0]); 
        myChart2.setOption(option[1]); 
        myChart3.setOption(option[2]); 
    }

});
</script>
</body>
</html>


