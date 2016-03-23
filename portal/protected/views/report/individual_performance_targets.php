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
            <select id="target_type">
                <option type="1">本月销售情况</option>
                <option type="2">本季销售情况</option>
                <option type="3">本年销售情况</option>
            </select>
        </h2>
    </div>
        <div class="chart_info mar_0">
        <i >员工:</i>
        <select id="staff">
            <option staff-id="1">邹佳佳</option>
            <option staff-id="2">李倩菱</option>
            <option staff-id="3">汪 莹</option>
            <option staff-id="4">刘佳妮</option>
            <option staff-id="5">叶锦文</option>
        </select>
    </div>
    <p class="chart_item_desc mar_t3">目标数：<i class="t_green" id="target_num">&yen;210000</i></p>
    <p class="chart_item_desc mar_b3">完成额：<i class="t_green mar_r10" id="complete_num">&yen;700000</i>完成度：<i class="t_green" id="complete_rate">30%</i></p>
    <div class="chart_bar" id="chart"></div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/charts/echarts.min.js"></script>
<script>
$(function(){

    //员工名称及ID，PHP获取
    //目标数、完成额、完成度，PHP获取，那JS如何调用？？？
    var target_num = 210000, complete_num = 120000;
    var complete_rate = (120000/210000 *100).toFixed(2);

    var width = $(document.body).width();
    $('#chart').css({'width': width, 'height': width*0.62});

    getChartOpt(complete_rate);
    //点击本月销售目标
    $("#target_type").on("change",function(){
        var selectText = $(this).find("option:selected").text();

        //此部分需要调用PHP渲染部分的complete_rate
        //$complete_rate

        //然后调用complete_rate
        var complete_rate = 15
        getChartOpt(complete_rate);
    });

    //点击选择员工
    $("#staff").on("change",function(){
        var selectText = $(this).find("option:selected").text();

        //此部分需要PHP传回一个complete_rate
        //$complete_rate

        //然后调用complete_rate
        var complete_rate = 20
        getChartOpt(complete_rate);
    });



    function getChartOpt(complete_rate){
        // 基于准备好的dom，初始化echarts图表
        var myChart = echarts.init(document.getElementById('chart')); 
        var a = parseInt(complete_rate);
        var option = {
        tooltip : {
            formatter: "{a} <br/>{b} : {c}%"
        },
        series : [
            {
                name:'业务指标',
                type:'gauge',
                startAngle: 180,
                endAngle: 0,
                center : ['50%', '60%'],    // 默认全局居中
                radius : '120%',
                axisLine: {            // 坐标轴线
                    lineStyle: {       // 属性lineStyle控制线条样式
                        color: [[0.5, '#d9434b'],[0.8, '#48b'],[1.0, '#00b90c']], 
                        width: 55
                    }
                },
                axisTick: {            // 坐标轴小标记
                    splitNumber: 10,   // 每份split细分多少段
                    length :12,        // 属性length控制线长
                },
                axisLabel: {           // 坐标轴文本标签，详见axis.axisLabel
                    formatter: function(v){
                        switch (v+''){
                            case '10': return '低';
                            case '50': return '中';
                            case '90': return '高';
                            default: return '';
                        }
                    },
                    textStyle: {
                        color: '#fff',
                        fontSize: 15,
                        fontWeight: 'bolder'
                    }
                },
                pointer: {
                    width:10,
                    length: '80%',
                    color: 'rgba(185, 185, 185, 0.8)'
                },
                detail : {
                    show : true,
                    backgroundColor: 'rgba(0,0,0,0)',
                    borderWidth: 0,
                    borderColor: '#ccc',
                    width: 50,
                    height: 20,
                    offsetCenter: [0, -15],
                    formatter:'{value}%',
                    textStyle: {
                        fontSize : 15
                    }
                },
                data:[{value: a, name: '完成率'}]
            }]
        };

        // 为echarts对象加载数据 
        myChart.setOption(option); 
    };
});
</script>
</body>
</html>
