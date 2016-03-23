<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>昨日经营日报</title>
<meta name="viewport" content="width=640,target-densitydpi=device-dpi,user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.7.min.js"></script>
<script type="text/javascript" src="js/thorn.js"></script>
<link rel="stylesheet" href="css/thorn.css">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>

<article>
<div class="mian_hs">
	<div class="product_date">
    	<div class="title">
        	<p class="p1"><span>昨日经营日报</span></p>
            <p class="p2">让销售业绩飞起来</p>
            <!-- <div class="ww">
<pre>
管好产品，管好过程，管好业绩
三步走，销冠的秘诀就是这么容易
从初访到复盘，武装销售全过程
一直在路上的你，用移动CRM轻装上阵吧
</pre>
		</div> -->
        </div>
        <div class="nrt">
        	<div class="itme">
                <div class="wz"><p class="name"><span>进店走势</span></p></div>
                <div class="chart_bar" id="chart" style='width:640px;height:320px;'></div>
            </div>

            <div class="itme">
                <div class="wz"><p class="name"><span>已签订单</span></p>
<!-- <pre>
谈客户就像谈恋爱，因为懂得所以获得
初见，相识，相知，打造客户跟进全记录
语音，文字，图片，关联客户，碎片化信息随手记录不丢失
服务过程记明白，销售业绩飞起来
</pre> -->
                </div>
                
            	<div class="tu chart_bar" id="order_sure" style='width:640px;height:600px;' ></div>
            </div>
            
            <div class="itme">
                <div class="wz"><p class="name"><span>销售额</span></p></div>
                <div style='display:inline-block;width:640' >
                    <div style='display:inline-block;width:200px;'>
                        <ul>
                            <li>
                                <p>目标</p>
                                <p>67,000元</p>
                            </li>
                            <li>
                                <p>预测</p>
                                <p>67,000元</p>
                            </li>
                            <li>
                                <p>成交</p>
                                <p>67,000元</p>
                            </li>
                            <li>
                                <p>回款</p>
                                <p>67,000元</p>
                            </li>
                        </ul>
                    </div>
                    <div style='display:inline-block;width:300px;'>
                        <div style='display:inline-block;width:430px;height:400px;' id='sales'></div>
                    </div>
                </div>
            </div>
            
            
        </div>
    </div>



</div>


<div class="bottom_hs">
	<!-- <div class="tu"><img src="css/img/ewm.jpg" /></div> -->
    <div class="wz">©北京浩瀚一方互联网科技有限责任公司</div>
</div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/charts/echarts.min.js"></script>
<!-- <script src="js/charts/line.js"></script> -->
<script src="js/common.js"></script>
<script>
$(function(){
        /*******************************************
            画图表横坐标，周、日等
            传入type：0（周报），1（月报），2（季报），3（年报），以及后台提供的json
        ********************************************/   
        function draw_xAxis(type){
            var xAxis_data = new Array();

            var tempdate = new Date();
            var month = tempdate.getMonth()+1;
            var year = tempdate.getFullYear();

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
            return xAxis_data;
        }

        /*******************************************
        画第一个折线图
        后台发起请求，数据渲染界面
        传入报表1类型type：'0'（周报），'1'（月报），'2'（季报），'3'（年报）
                  分店的ID
        ********************************************/   
        function draw_mychart1(type,hotel_id){
            //获取某个主题酒店的开单信息
            var temp_right_date=new Date();
           
            var temp_stamp =  Date.parse(temp_right_date) / 1000;
           $.getJSON('<?php echo $this->createUrl("report/info");?>',{hotel_id: 1,chart1_type:'1' ,show_date:temp_stamp,show_day:0
           },function(retval){
           // alert(retval);
               var ret = JSON.stringify(retval);
              // console.log(ret);
               if(retval.success){
                //retval格式，wedding{every_num:[],totle_num,rate,rate_type},meeting{every_num:[],totle_num,rate,rate_type}
                   console.log(ret);

                    //横坐标显示内容

                    var myChart2 = echarts.init(document.getElementById('chart')); 
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
                                data:retval.wedding.every_num,
                            },
                            {
                                name:'会议',
                                type:'line',
                                data:retval.meeting.every_num,
                            }
                        ]
                    };
                    // 为echarts对象加载数据 
                    myChart2.setOption(option);               
              // }else{
              //   alert('太累了，歇一歇，一秒后再试试！');
              //   return false;
               }
             });
        };


        /********************************************************************************************
        已签订单
        ********************************************************************************************/

        var xAxis_data = draw_xAxis('1');
        draw_mychart1('1',1);



        var wedding = new Array();
        var i = 11;
        <?php foreach ($order_sure['wedding'] as $key => $value) {?>
            wedding[i] = <?php echo $value;?>;
            i--;
        <?php }?>

        var meeting = new Array();
        var j = 11;
        <?php foreach ($order_sure['meeting'] as $key => $value) {?>
            meeting[j] = <?php echo $value;?>;
            j--;
        <?php }?>
        console.log(meeting);
        console.log(wedding);

        draw_mychart_ordersure();
        

        function draw_mychart_ordersure(){
            var myChart_ordersure = echarts.init(document.getElementById('order_sure')); 
            var option = {
                tooltip : {
                    trigger: 'axis',
                    axisPointer : {            // 坐标轴指示器，坐标轴触发有效
                        type : 'shadow'        // 默认为直线，可选为：'line' | 'shadow'
                    }
                },
                grid : {  
                            x : 40,
                            y : 40,
                            x2: 40,
                            y2: 40
                },
                legend: {
                    data:['婚礼', '会议']
                },
                calculable : true,
                xAxis : [
                    {
                        type : 'value'
                    }
                ],
                yAxis : [
                    {
                        type : 'category',
                        data : ['12月','11月','10月','9月','8月','7月','6月','5月','4月','3月','2月','1月']
                    }
                ],
                series : [
                    {
                        name:'婚礼',
                        type:'bar',
                        stack: '总量',
                        itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
                        data: wedding
                    },
                    {
                        name:'会议',
                        type:'bar',
                        stack: '总量',
                        itemStyle : { normal: {label : {show: true, position: 'insideRight'}}},
                        data:meeting
                    }
                ]
            };
            // 为echarts对象加载数据 
            myChart_ordersure.setOption(option);
        }

        /********************************************************************************************
        画图3，销售额图
        ********************************************************************************************/
        //数据处理
        var target = <?php echo $sales['target'];?>;      //目标
        var forecast = <?php echo $sales['forecast'];?>;  //预测
        var deal = <?php echo $sales['deal'];?>;          //成交
        var payment = <?php echo $sales['payment'];?>;    //回款

        var sales_data = new Array();
        sales_data[0]=target, sales_data[1] = forecast, sales_data[2] = deal, sales_data[3] = payment;
        function draw_mychart_sales(){
            var myChart_sales = echarts.init(document.getElementById('sales')); 
            var option = {
                tooltip: {
                    trigger: 'item'
                },
                grid : {  
                            x : 40,
                            y : 40,
                            x2: 40,
                            y2: 40
                },
                xAxis: [
                    {
                        type: 'category',
                        show: false,
                        data: ['目标', '预测', '成交', '回款']
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        show: false
                    }
                ],
                series: [
                    {
                        name: 'ECharts例子个数统计',
                        type: 'bar',
                        itemStyle: {
                            normal: {
                                color: function(params) {
                                    // build a color map as your need.
                                    var colorList = [
                                      '#C1232B','#B5C334','#FCCE10','#E87C25','#27727B',
                                       '#FE8463','#9BCA63','#FAD860','#F3A43B','#60C0DD',
                                       '#D7504B','#C6E579','#F4E001','#F0805A','#26C0C0'
                                    ];
                                    return colorList[params.dataIndex]
                                },
                                label: {
                                    show: true,
                                    position: 'top',
                                    formatter: '{b}\n{c}'
                                }
                            }
                        },
                        data: sales_data
                    }
                ]
            };
        // 为echarts对象加载数据 
        myChart_sales.setOption(option);

        }


});
</script>
</body>
</html>

<script src="js/zepto.min.js"></script>
<script src="js/charts/echarts.min.js"></script>
<!-- <script src="js/charts/line.js"></script> -->
<script src="js/common.js"></script>
<script>
$(function(){

    });
</script>