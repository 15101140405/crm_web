<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>上传</title>
    <link rel="stylesheet" type="text/css" href="css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <link rel="stylesheet" type="text/css" href="css/upload_set.css" />
</head>

<body style="background:#fff;">
    <!--头部-->
    <div class="upload_top">
        <div class="upload_wapper clearfix">
            <h1 class="logo left"><img src="images/logo.jpg" alt=""></h1>
            <span class="nick right">best</span>
        </div>
    </div>
    <!--导航-->
    <div class="nav_area">
        <div class="upload_wapper">
            <ul class="nav_list upload_wapper clearfix">
                <li><a href="#">场地布置</a>
                    <ul class="sub_nav_list" id="decoration">
                        <li><a href="#">全部</a>
                        </li>
                        <li><a href="#">全部全部</a>
                        </li>
                        <li><a href="#">全部</a>
                        </li>
                        <li><a href="#">全部全部</a>
                        </li>
                    </ul>
                </li>
                <li>|</li>
                <li id="host"><a href="#">主持</a>
                </li>
                <li>|</li>
                <li id="video"><a href="#">摄像</a>
                </li>
                <li>|</li>
                <li id="camera"><a href="#">摄影</a>
                </li>
                <li>|</li>
                <li id="makeup"><a href="#">化妆</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="upload_set_c upload_wapper clearfix">
        <!--左侧内容区域-->
        <div class="left_area left">
            <ul class="goods_list clearfix">
                <li>
                    <div class="img_box">
                        <img src="images/cover.jpg" alt="">
                        <span>已售233件</span>
                    </div>
                    <div class="info_box">
                        <p class="name">商品名称简介</p>
                        <p class="price">&yen;<strong>500.00</strong>
                        </p>
                        <p class="original_price">&yen;<del>400.00</del>
                        </p>
                        <button class="add_product">立即购买</button>
                    </div>
                </li>
            </ul>
        </div>
        <!--右侧内容区域-->
        <div class="right_area right">
            <div>
                <div class="tit_box clearfix" style="width:230px;">
                    <h2 class="left">最新加入的宝贝</h2>
                    <a href="#" class="right">查看更多</a>
                </div>
                <ul class="add_list" style="width:230px;" id="shopping_car">
                    <li class="clearfix">
                        <img class="left" src="images/cover.jpg" alt="">
                        <div class="con left">
                            <h3>标题标题标题标题标题</h3>
                            <div class="counter_box clearfix">
                                <span class="minus_btn btn disabled left">-</span>
                                <input class="count left" type="text" readOnly="true" value="1">
                                <span class="add_btn btn left">+</span>
                            </div>
                        </div>
                        <p class="right unit_price">&yen;
                            <input type="text" value="100">
                        </p>
                    </li>
                </ul>
                 
            </div>
           <div class="button_box">
                    &yen;
                    <span>600.00</span> 购物车结算
                </div>
        </div>
    </div>

    <!--底部-->
    <div class="footer">
        <ul class="footer_link_list clearfix">
            <li><a href="javascript:;">关于我们</a>
            </li>
            <li>|</li>
            <li><a class="active" href="javascript:;">关于我们</a>
            </li>
            <li>|</li>
            <li><a href="javascript:;">关于我们</a>
            </li>
            <li>|</li>
            <li><a href="javascript:;">关于我们</a>
            </li>
        </ul>
        <p>京公网安备11010502022785号 京公网安备11010502022785号</p>
        <p>京公网安备11010502022785号</p>
    </div>
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/upload_set.js"></script>
<script>
    $(function(){
        //点击加入套系
        $(".add_product").on("click",function(){
            html = '<li class="clearfix hid" product-id="'+123+'">'+
                        '<img class="left" src="'+'images/cover.jpg'+'" alt="">'+
                        '<div class="con left">'+
                            '<h3>'+'标题标题标题标题标题'+'</h3>'+
                            '<div class="counter_box clearfix">'+
                                '<span class="minus_btn btn disabled left">-</span>'+
                                '<input class="count left" type="text" readonly="true" value="1">'+
                                '<span class="add_btn btn left">+</span>'+
                            '</div>'+
                        '</div>'+
                        '<p class="right unit_price">¥'+
                            '<input type="text" value="'+100+'">'+
                        '</p>'+
                    '</li>';
            $("#shopping_car").prepend(html);
            $(".hid").fadeIn();
        });

    })
</script>
</body>

</html>