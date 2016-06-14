<!DOCTYPE html>
<html>

<head>
    <title>经营日报</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base_daily_management.css">
    <link rel="stylesheet" type="text/css" href="css/daily_management.css">

</head>

<body style="background:#4b4b57">
    <h1 class="title"><?php echo $hotel_name?>［<?php echo date("Y-m-d",time())?>］</h1>
    <!--导航-->
    <ul class="navlist flexbox">
        <li>
            <a href="javascript:;">
                <img src="images/set01.png" alt="">
                <p>今日简报</p>
            </a>
        </li>
        <li>
            <a href="javascript:;">
                <img src="images/set01.png" alt="">
                <p>订单汇总</p>
            </a>
        </li>
        <!-- <li>
            <a href="javascript:;">
                <img src="images/set01.png" alt="">
                <p>标题</p>
            </a>
        </li>
        <li>
            <a href="javascript:;">
                <img src="images/set01.png" alt="">
                <p>标题</p>
            </a>
        </li>
        <li>
            <a href="javascript:;">
                <img src="images/set01.png" alt="">
                <p>标题</p>
            </a>
        </li> -->
    </ul>
    <!--内容区1-->
    <section class="daily_container">
        <div class="flexbox v_center title_box">
            <img src="images/set01.png" alt="">
            <h2 class="green">今日经营概览</h2>
        </div>
        <ul class="daily_list">
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/man_icon.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $indoor;?></em>个</p>
                    <p>今日进店</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/man_icon.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $open_order?></em>个</p>
                    <p>今日开单</p>
                </div>
            </li>
        </ul>
    </section>
    <!--内容区1-->
    <section class="daily_container">
        <div class="flexbox v_center title_box">
            <img src="images/set01.png" alt="">
            <h2 class="green">累计订单简报［<?php echo date('Y')?>～］</h2>
        </div>
        <ul class="daily_list">
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/man_icon.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $wedding_all?></em>个</p>
                    <p>婚礼成单</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/man_icon.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $meeting_all?></em>个</p>
                    <p>会议成单</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/man_icon.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $wedding_doing?></em>个</p>
                    <p>待执行－婚礼</p>
                </div>
            </li>
            <li class="flexbox">
                <div class="imgbox">
                    <img src="images/man_icon.png" alt="">
                </div>
                <div class="info_box">
                    <p><em><?php echo $meeting_doing?></em>个</p>
                    <p>待执行－会议</p>
                </div>
            </li>
        </ul>
    </section>
    <!--内容区2-->
    <section class="daily_container">
        <div class="flexbox v_center title_box">
            <img src="images/set01.png" alt="">
            <h2 class="blue flex1">销售详情（<?php echo date('Y')?>）</h2>
            <img src="images/set01.png" alt="">
        </div>
        <div class="flexbox">
            <ul class="target_list">
                <li class="flexbox">
                    <div class="img_box">
                        <img src="images/man_icon.png" alt="">
                    </div>
                    <div class="info_box">
                        <p>目标</p>
                        <p><em><?php echo number_format($hotel_target,1)?></em>万</p>
                    </div>
                </li>
                <li class="flexbox">
                    <div class="img_box">
                        <img src="images/man_icon.png" alt="">
                    </div>
                    <div class="info_box">
                        <p>销售额</p>
                        <p><em><?php echo number_format($hotel_total_sales/10000,1)?></em>万</p>
                    </div>
                </li>
                <li class="flexbox">
                    <div class="img_box">
                        <img src="images/man_icon.png" alt="">
                    </div>
                    <div class="info_box">
                        <p>回款</p>
                        <p><em><?php echo number_format($order_total_payment/10000,1)?></em>万</p>
                    </div>
                </li>
            </ul>
            <div class="flex1">
                <div id="main" class="main" style="height:300px;"></div>
            </div>
        </div>


    </section>

    <script src="js/echarts.js"></script>
    <script type="text/javascript">
        require.config({
            paths: {
                echarts: './js'
            }
        });
        require(
        [
            'echarts',
            'echarts/chart/bar',
        ],
            function (ec) {
                var myChart = ec.init(document.getElementById('main'));
                myChart.setOption({
                    // title: {
                    //     x: 'right',
                    //     text: '06月01日 - 06月30日',
                    //     textStyle: {
                    //         fontSize: 18,
                    //         fontWeight: 'normal',
                    //         color: '#999'
                    //     }

                    // },
                    tooltip: {
                        trigger: 'axis'
                    },

                    toolbox: {
                        show: false,
                        feature: {
                            mark: {
                                show: true
                            },
                            dataView: {
                                show: true,
                                readOnly: false
                            },
                            magicType: {
                                show: true,
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true
                            },
                            saveAsImage: {
                                show: true
                            }
                        }
                    },
                    grid: {
                        borderWidth: 0,
                        y: 80,
                        y2: 60
                    },
                    xAxis: [
                        {
                            type: 'category',
                            show: true,
                            splitLine: {show:false},
                            lineStyle: {
                                color: '#48b',
                                width: 0,
                                type: 'solid'
                            },

                            data: ['目标', '预测', '成交', '回款']
                    }
                ],
                    yAxis: [
                        {
                            type: 'value',
                            show: false,
                            splitArea: {
                                show: true
                            }
                    }
                ],
                    series: [
                        {
                            name: '目标值',
                            barCategoryGap: '20%',
                            itemStyle: {
                                normal: {
                                    color: function (params) {
                                        // build a color map as your need.
                                        var colorList = [
                          '#999', '#B5C334', '#FCCE10', '#E87C25',
                        ];
                                        return colorList[params.dataIndex]
                                    },


                                }
                            },
                            type: 'bar',
                            data: [4.0, 4.9, 7.0, 23.2]
                    },
                ]
                });

            }
        );
    </script>

</body>

</html>