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
</head>
<body>
<article>
    <div class="tool_bar">
        <h2 class="page_title">产品库</h2>
    </div>
    <nav>
        <ul>
            <li id='meeting_feast'>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/gendan.png"/>
                    </div>
                    <p class="cat_name">会议餐</p>
                </a>
            </li>
            <li id="changdifei">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">会议场地费</p>
                </a>
            </li>
            <li id="wedding_feast">
                <a href="<?php echo $this->createUrl("design/feast", array());?>&from=detail&tab=">
                    <div class="cat_icon">
                        <img src="images/hunyan.png"/>
                    </div>
                    <p class="cat_name">婚宴</p>
                </a>
            </li>
            <li id='service'>
                <a href="<?php echo $this->createUrl("design/servicePersonnel", array());?>&from=detail&tab=">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">服务人员</p>
                </a>
            </li>
            <li di='decoration'>
                <a href="<?php echo $this->createUrl("design/decoration", array());?>&from=detail">
                    <div class="cat_icon">
                        <img src="images/hunyan.png"/>
                    </div>
                    <p class="cat_name">场地布置</p>
                </a>
            </li>
            <li id="lighting">
                <a href="<?php echo $this->createUrl("design/lightingScreen", array());?>&from=detail&tab=">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">灯光视频</p>
                </a>
            </li>
            <li id='design'>
                <a href="<?php echo $this->createUrl("design/graphicFilm", array());?>&from=detail&tab=">
                    <div class="cat_icon">
                        <img src="images/queren.png"/>
                    </div>
                    <p class="cat_name">设计</p>
                </a>
            </li>
            <li id='dress'>
                <a href="<?php echo $this->createUrl("design/dressAppliance", array());?>&from=detail&tab=">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">婚纱婚品</p>
                </a>
            </li>
            <li id='car'>
                <a href="<?php echo $this->createUrl("design/drinksCar", array());?>&from=detail&tab=">
                    <div class="cat_icon">
                        <img src="images/dianli.png"/>
                    </div>
                    <p class="cat_name">酒水车辆</p>
                </a>
            </li>
            <li id='fee'>
                <a href="<?php echo $this->createUrl("design/planother", array());?>&from=detail&tab=">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">策划费&杂费</p>
                </a>
            </li>
        </ul>
    </nav>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script src="js/jquery.js"></script>
<script src="js/lc_switch.js" type="text/javascript"></script>
<script>
    $(function () {
        var from = "<?php echo $_GET['t']?>";
        if(from == 'wedding'){
            $("#meeting_feast").remove();
            $("#changdifei").remove();
        }else if(from == 'meeting'){
            $("#wedding_feast").remove();
            $("#service").remove();
            $("#decoration").remove();
            $("#lighting").remove();
            $("#design").remove();
            $("#dress").remove();
            $("#car").remove();
            $("#fee").remove();
        }
    })
</script>
</body>
</html>
