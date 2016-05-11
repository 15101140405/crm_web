<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>产品库</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/lc_switch.css">
    <link rel="stylesheet" type="text/css" href="css/order.css">
</head>
<body>
<article>
    <!-- <div class="tool_bar">
        <h2 class="page_title">产品库</h2>
    </div> -->
    <nav class="fixed_nav" id="main_nav">
        <ul>
            <li id="product_store" class="active">
                <span></span>
                <p class="cat_name">产品库</p>
            </li>
            <li id="index">
                <span></span>
                <p class="cat_name">档期</p>
            </li>
            <li id="order">
                <span></span>
                <p class="cat_name">订单</p>
            </li>
            <li id="finance_report">
                <span></span>
                <p class="cat_name">财报</p>
            </li>
        </ul>
    </nav>
    <nav style='margin-bottom:80px;'>
        <ul id="product">
            <li supplier-type="2" category="2">
                <a href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=2&category=2">
                    <div class="cat_icon">
                        <img src="images/hunyan.png"/>
                    </div>
                    <p class="cat_name">婚宴</p>
                </a>
            </li>
            <li supplier-type="2" category="1">
                <a href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=2&category=1">
                    <div class="cat_icon">
                        <img src="images/gendan.png"/>
                    </div>  
                    <p class="cat_name">会议餐</p>
                </a>
            </li>
            <li supplier-type="22" category="2">
                <a href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=22&category=2">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">婚礼套系</p>
                </a>
            </li>
            <li supplier-type="22" category="1">
                <a href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=22&category=1">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">会议套系</p>
                </a>
            </li>
            <li supplier-type="20" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/hunyan.png"/>
                    </div>
                    <p class="cat_name">婚礼场布</p>
                </a>
            </li>
            <li supplier-type="3" category="2">
                <a href="<?php echo $this->createUrl("service/teamlist");?>&service_type=3">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">主持人</p>
                </a>
            </li>
            <li supplier-type="4" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">摄像师</p>
                </a>
            </li>
            <li supplier-type="5" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">摄影师</p>
                </a>
            </li>
            <li supplier-type="6" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">化妆师</p>
                </a>
            </li>
            <li supplier-type="7" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">其他人员</p>
                </a>
            </li>
            <li supplier-type="8" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">灯光</p>
                </a>
            </li>
            <li supplier-type="9" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">视频</p>
                </a>
            </li>
            <li supplier-type="10" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/queren.png"/>
                    </div>
                    <p class="cat_name">平面设计</p>
                </a>
            </li>
            <li supplier-type="11" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/queren.png"/>
                    </div>
                    <p class="cat_name">视频设计</p>
                </a>
            </li>
            <li supplier-type="12" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">婚纱礼服</p>
                </a>
            </li>
            <li supplier-type="13" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">婚礼用品</p>
                </a>
            </li>
            <li supplier-type="14" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dianli.png"/>
                    </div>
                    <p class="cat_name">酒水</p>
                </a>
            </li>
            <li supplier-type="15" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dianli.png"/>
                    </div>
                    <p class="cat_name">车辆</p>
                </a>
            </li>
            <li supplier-type="17s" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">策划费</p>
                </a>
            </li>
            <li supplier-type="21" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">杂费</p>
                </a>
            </li>
            <!-- <li supplier-type="18" category="1">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">会议税费</p>
                </a>
            </li>
            <li supplier-type="18" category="2">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">婚礼税费</p>
                </a>
            </li> -->
        </ul>
    </nav>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script src="js/jquery.js"></script>
<script src="js/lc_switch.js" type="text/javascript"></script>
<script type="text/javascript" src='js/nav.js'></script>
<script>
    $(function () {
        $("#product li").on("click",function(){
            if($(this).attr("supplier-type") != "18"){
                location.href = "<?php echo $this->createUrl('product/list');?>&supplier_type=" + $(this).attr("supplier-type") + "&category=" + $(this).attr("category");
            };
        });

        //导航
        $("#product_store").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/store');?>&code=&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
        });
        $("#index").on("click",function(){
            location.href = "<?php echo $this->createUrl('order/index');?>&from=";
        });
        $("#order").on("click",function(){
            location.href = "<?php echo $this->createUrl('order/order');?>";
        });
        $("#finance_report").on("click",function(){
            
            location.href = "<?php echo $this->createUrl('report/financereport');?>";
        });

    })
</script>
</body>
</html>
