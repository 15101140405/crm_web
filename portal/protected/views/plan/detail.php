<?php
/*$arr = array(//+++++++++++++++++++++++++++所需数据
    'order_name' => '北京浩瀚一方互联网科技',
    'update_time' => '2015 12 12',
);
*/
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>婚礼统筹</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article>
    <section class="order_detail_banner">
        <h4 class="title"><?php echo $arr['order_name']; ?></h4>
        <p class="desc"><?php echo $arr['update_time']; ?><!-- <span class="status"></span> --></p>
        <p class="icon_btn_bar">
            <a class="icon_btn" data-icon="&#xe6e5;" href="<?php echo $this->createUrl("plan/detailInfo", array());?>&from=plan&order_id=<?php echo $_GET['order_id']?>">详细资料</a>
            <a class="icon_btn" data-icon="&#xe6e0;" href="<?php echo $this->createUrl("design/bill", array());?>&from=plan&order_id=<?php echo $_GET['order_id']?>">报价单</a>
            <a class="icon_btn" data-icon="&#xe736;" href="<?php echo $this->createUrl("order/transition", array());?>&from=plan">转移客户</a>
        </p>
        <span class="abs_back_btn" data-icon="&#xe679;"></span>
    </section>
    <nav>
        <ul>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/gendan.png"/>
                    </div>
                    <p class="cat_name">跟单</p>
                </a>
            </li>
            <li id="designer">
                <a>
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">策划师</p>
                </a>
            </li>
            <li id="feast">
                <a>
                    <div class="cat_icon">
                        <img src="images/hunyan.png"/>
                    </div>
                    <p class="cat_name">婚宴</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">套系</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/tishi.png"/>
                    </div>
                    <p class="cat_name">婚前提示</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/queren.png"/>
                    </div>
                    <p class="cat_name">供应商确认</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dingzhuhui.png"/>
                    </div>
                    <p class="cat_name">家人叮嘱会</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">督导会</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/jieqin.png"/>
                    </div>
                    <p class="cat_name">接亲流程</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dianli.png"/>
                    </div>
                    <p class="cat_name">典礼流程</p>
                </a>
            </li>
        </ul>
    </nav>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {

        set_local();
        //order_id放入本地存储
        function set_local() {
            if ($.util.param("from") == "index" || $.util.param("from") == "my_order") {
                localStorage.setItem("order_id", $.util.param("order_id"));
                localStorage.setItem("from", $.util.param("from"));
            }
        }

        //点击返回，删除本地存储
        $(".abs_back_btn").on("click", function () {
            var from = localStorage.getItem("from");
            localStorage.removeItem("order_id");
            localStorage.removeItem("from");
            if (from == "index") {
               location.href = "<?php echo $this->createUrl("order/index", array());?>"
            } else if (from == "my_order") {
                location.href = "<?php echo $this->createUrl("order/my", array());?>&t=plan"
            }
        })

        //点击选择策划师，跳转
        $("#designer").on("click", function () {
            location.href = "<?php echo $this->createUrl("plan/selectDesigner", array());?>&from=" + $.util.param("from");
        })


    })
</script>
</body>
</html>