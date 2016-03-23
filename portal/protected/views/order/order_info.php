<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单列表－订单详情</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/framework7.ios.min.css">
    <link rel="stylesheet" href="css/framework7.ios.colors.min.css">
    <!-- <link rel="stylesheet" href="css/framework7.material.min.css"> -->
    <link rel="stylesheet" href="css/framework7.material.colors.min.css">
    <link rel="stylesheet" href="css/upscroller.css">
    <link rel="stylesheet" href="css/my-app.css">
    <link rel="stylesheet" href="css/index.css">
    <!-- <link rel="stylesheet" href="css/paging.css"> -->
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/swiper.min.css">
</head>
<body>
<article id="homepage" >
    <div class="tool_bar fixed">
        <!-- <div class="l_btn" data-icon="&#xe69c;" id="filter"></div> -->
        <h2 class="page_title" id="pa_title">订单详情</h2>
        <!--管理层显示该title -->
        <!-- <div class="r_btn" data-icon="&#xe767;"></div> -->
    </div>
    <ul class="m-index-list" id="page_list">
        <li class="card " category="appear" status="进行中">
            <h6  style="text-align: center;color:#37CB58;font-size: 2rem;">黄晓明&Baby</h6>
            <h6  style="text-align: center;color:#b6babf;font-size: 1.2rem;">婚礼日期：2015-5-5</h6>
        </li>
        <li class="card list_more" category="appear" status="进行中">
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 2rem;">当前报价</h6>
            <div class="m-money clear">
                <p class="m-money-num fl" >￥<span>62,918</span></p>
                <p class="m-money-per fr">利润率：<span rate="1746">629.18</span>%</p>
            </div>
            <div class="progress"><span class="progress-now fl" style="width:100%;"></span></div>
            <div class="target">
                <p class="target-money fl">成本：￥<span>10,000</span></p>
                            <p class="rest-time fr">利润：<span class="show_time_1746">48天</span></p>
                    </div>
        </li>

        <li class="card list_more" category="appear" status="进行中">
            <h6 class="m-list-tit" style="color:#37CB58;;font-size: 1.8rem;text-align: left;margin-left: 10px;">成交</h6>
            <div class="m-money clear" style="display:inline">
                <div style="display:inline-block;float:left;margin-left:5%;">
                    <p class="m-money-per ">成交金额</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;">￥<span>62,918</span></p>
                    <p class="m-money-per ">利润率：<i>65%</i></p>
                </div>
                <div style="display:inline-block;float:right;margin-right:15%;">
                    <p class="m-money-per ">累计回款</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;" >￥<span>62,918</span></p>
                    <p class="m-money-per ">回款比例：<i>25%</i></p>
                </div>
            </div>
        </li>
        <li class="card list_more" category="appear" status="进行中">
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 1.8rem;text-align: left;margin-left: 10px;">跟进情况 <span style="color:#b6babf;font-size:1rem;"> [李倩菱]</span></h6>
            <div class="m-money clear" style="display:inline">
                <div style="display:inline-block;float:left;margin-left:15%;border-right:1px solid #ebebeb;width:35%">
                    <p class="m-money-per " >进店面谈</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;">1 <i class="m-money-per "> 次</i></p>
                </div>
                <div style="display:inline-block;float:right;margin-right:15%;">
                    <p class="m-money-per ">跟单</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;">2 <i class="m-money-per "> 次</i></p>
                </div>
            </div>
        </li>


        <!-- <li class="card" category="game" status="进行中">
            <h6 class="m-list-tit">
                <a data-category="game" href="#"><span><img class="m-tit-bg" src="http://s.moimg.net//m_201505/images/header-game-active.png">游戏</span></a>
            </h6>
            <div class="m-list-con clear">
                <a class="m-list-left fl" href="http://zhongchou.modian.com/item/1858.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap"><img src="http://u.moimg.net/project/project_20160305_1457116430_9899_400x300.jpg" alt=""></a>
                <div class="m-word-con fr" style="width: 173px;">
                    <p class="m-list-word"><a href="http://zhongchou.modian.com/item/1858.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap">原创文字冒险解谜AVG《神明的一天世界》</a></p>
                    <p class="m-people-num">支持人数：<span>119</span>人</p>
                </div>
            </div>
            <div class="m-money clear">
                <p class="m-money-num fl">￥<span>17,313</span></p>
                <p class="m-money-per fr">已到达：<span rate="1858">173.13</span>%</p>
            </div>
            <div class="progress"><span class="progress-now fl" style="width:100%;"></span></div>
            <div class="target">
                <p class="target-money fl">目标金额：￥<span>10,000</span></p>
                            <p class="rest-time fr">剩余时间：<span class="show_time_1858">48天</span></p>
                    </div>
        </li><li class="card" category="cartoon" status="进行中">
            <h6 class="m-list-tit">
                <a data-category="cartoon" href="#"><span><img class="m-tit-bg" src="http://s.moimg.net//m_201505/images/header-cartoon-active.png">动漫</span></a>
            </h6>
            <div class="m-list-con clear">
                <a class="m-list-left fl" href="http://zhongchou.modian.com/item/1800.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap"><img src="http://u.moimg.net/project/project_20160226_1456479704_7173_400x300.jpg" alt=""></a>
                <div class="m-word-con fr" style="width: 173px;">
                    <p class="m-list-word"><a href="http://zhongchou.modian.com/item/1800.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap">2016上海华夏萌驹祭CNPonyCon</a></p>
                    <p class="m-people-num">支持人数：<span>544</span>人</p>
                </div>
            </div>
            <div class="m-money clear">
                <p class="m-money-num fl">￥<span>79,267</span></p>
                <p class="m-money-per fr">已到达：<span rate="1800">158.53</span>%</p>
            </div>
            <div class="progress"><span class="progress-now fl" style="width:100%;"></span></div>
            <div class="target">
                <p class="target-money fl">目标金额：￥<span>50,000</span></p>
                            <p class="rest-time fr">剩余时间：<span class="show_time_1800">31天</span></p>
                    </div>
        </li><li class="card" category="appear" status="进行中">
            <h6 class="m-list-tit">
                <a data-category="appear" href="#"><span><img class="m-tit-bg" src="http://s.moimg.net//m_201505/images/header-appear-active.png">出版</span></a>
            </h6>
            <div class="m-list-con clear">
                <a class="m-list-left fl" href="http://zhongchou.modian.com/item/1978.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap"><img src="http://u.moimg.net/project/project_20160322_1458637497_6094_400x300.png" alt=""></a>
                <div class="m-word-con fr" style="width: 173px;">
                    <p class="m-list-word"><a href="http://zhongchou.modian.com/item/1978.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap">《游戏设计梦工厂》与《游戏设计艺术》墨战具联合摩点网独家首发</a></p>
                    <p class="m-people-num">支持人数：<span>26</span>人</p>
                </div>
            </div>
            <div class="m-money clear">
                <p class="m-money-num fl">￥<span>5,056</span></p>
                <p class="m-money-per fr">已到达：<span rate="1978">168.53</span>%</p>
            </div>
            <div class="progress"><span class="progress-now fl" style="width:100%;"></span></div>
            <div class="target">
                <p class="target-money fl">目标金额：￥<span>3,000</span></p>
                            <p class="rest-time fr">剩余时间：<span class="show_time_1978">60天</span></p>
                    </div>
        </li><li class="card" category="game" status="进行中">
            <h6 class="m-list-tit">
                <a data-category="game" href="#"><span><img class="m-tit-bg" src="http://s.moimg.net//m_201505/images/header-game-active.png">游戏</span></a>
            </h6>
            <div class="m-list-con clear">
                <a class="m-list-left fl" href="http://zhongchou.modian.com/item/1415.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap"><img src="http://u.moimg.net/project/project_20160321_1458552588_2436_400x300.jpg" alt=""></a>
                <div class="m-word-con fr" style="width: 173px;">
                    <p class="m-list-word"><a href="http://zhongchou.modian.com/item/1415.html?_mdsf=home_czpro_wap&amp;_mpos=h_czpro_wap">Story Of Legends —— 平面ARPG沙盒游戏</a></p>
                    <p class="m-people-num">支持人数：<span>2</span>人</p>
                </div>
            </div>
            <div class="m-money clear">
                <p class="m-money-num fl">￥<span>106</span></p>
                <p class="m-money-per fr">已到达：<span rate="1415">0.05</span>%</p>
            </div>
            <div class="progress"><span class="progress-now fl" style="width:0.05%;"></span></div>
            <div class="target">
                <p class="target-money fl">目标金额：￥<span>200,000</span></p>
                            <p class="rest-time fr">剩余时间：<span class="show_time_1415">39天</span></p>
                    </div>
        </li> -->
    </ul>
</article>
