<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>婚礼策划</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/lc_switch.css">
<style type="text/css">
body * {
  font-family: Arial, Helvetica, sans-serif;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
h1 {
  margin-bottom: 10px;
  padding-left: 35px;
}
a {
  color: #888;
  text-decoration: none;
}
small {
  font-size: 13px;
  font-weight: normal;
  padding-left: 10px;
}
#first_div {
  width: 90%;
  max-width: 600px;
  min-width: 340px;
  margin: 50px auto 0;
  color: #444;
}
#second_div {
  width: 90%;
  max-width: 600px;
  min-width: 340px;
  margin: 50px auto 0;
  background: #f3f3f3;
  border: 6px solid #eaeaea;
  padding: 20px 40px 40px;
  text-align: center;
  border-radius: 2px;
}
#third_div {
  width: 90%;
  max-width: 600px;
  min-width: 340px;
  margin: 10px auto 0;
}
</style>
</head>
<body>
<article>
    <section class="order_detail_banner">

        <?php
        /*$arr = array(
            'order_name' => '李晨  &范冰冰范冰',
            'order_time' => 'YYYY-MM-DD',
        );*/

        ?>
        <h4 class="title"><?php echo $arr['order_name']; ?></h4>
        <p class="desc"><?php echo $arr['order_date']; ?></p>
        <p id='booking' style="display:inline-block;position: absolute;top: 10%;left: 75%">
          <input id='switch' type="checkbox" name="check-1" value="4" class="lcs_check" autocomplete="off" />
        </p>
        <p class="icon_btn_bar" style="padding:0px;">
            <a class="icon_btn" style="font-size: 1.5rem;margin-left:4%;" data-icon="&#xe64c;" href="<?php echo $this->createUrl("finance/cashier", array());?>&order_id=<?php echo $_GET['order_id']?>&from=design">收款记录</a>
            <a class="icon_btn" style="font-size: 1.5rem;margin-left:4%;" data-icon="&#xe6e5;" href="<?php echo $this->createUrl("plan/detailInfo", array());?>&order_id=<?php echo $_GET['order_id']?>&from=design">详细资料</a>
            <a class="icon_btn" style="font-size: 1.5rem;margin-left:4%;" data-icon="&#xe6e0;" href="<?php echo $this->createUrl("design/bill", array());?>&from=detail&order_id=<?php echo $_GET['order_id']?>">报价单</a>
            <a class="icon_btn" style="font-size: 1.5rem;margin-left:4%;" data-icon="&#xe736;" href="<?php echo $this->createUrl("order/transition", array());?>&from=design">转移客户</a>
        </p>
        <div class="abs_back_btn" data-icon="&#xe679;"></div>
    </section>
    <nav>
        <ul>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/gendan.png"/>
                    </div>
                    <p class="cat_name">跟单(#)</p>
                </a>
            </li>
            <li id="feast">
                <a href="<?php echo $this->createUrl("design/feast", array());?>&from=detail&tab=&order_id=<?php echo $_GET['order_id']?>">
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
                    <p class="cat_name">套系(#)</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/jieqin.png"/>
                    </div>
                    <p class="cat_name">接亲流程(#)</p>
                </a>
            </li>
            <li>
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/dianli.png"/>
                    </div>
                    <p class="cat_name">典礼流程(#)</p>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->createUrl("design/servicePersonnel", array());?>&from=detail&tab=&order_id=<?php echo $_GET['order_id']?>">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name">服务人员</p>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->createUrl("design/decoration", array());?>&from=detail&order_id=<?php echo $_GET['order_id']?>">
                    <div class="cat_icon">
                        <img src="images/hunyan.png"/>
                    </div>
                    <p class="cat_name">场地布置</p>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->createUrl("design/lightingScreen", array());?>&from=detail&tab=&order_id=<?php echo $_GET['order_id']?>">
                    <div class="cat_icon">
                        <img src="images/taoxi.png"/>
                    </div>
                    <p class="cat_name">灯光视频</p>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->createUrl("design/graphicFilm", array());?>&from=detail&tab=&order_id=<?php echo $_GET['order_id']?>">
                    <div class="cat_icon">
                        <img src="images/queren.png"/>
                    </div>
                    <p class="cat_name">设计</p>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->createUrl("design/dressAppliance", array());?>&from=detail&tab=&order_id=<?php echo $_GET['order_id']?>">
                    <div class="cat_icon">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <p class="cat_name">婚纱婚品</p>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->createUrl("design/drinksCar", array());?>&from=detail&tab=&order_id=<?php echo $_GET['order_id']?>">
                    <div class="cat_icon">
                        <img src="images/dianli.png"/>
                    </div>
                    <p class="cat_name">酒水车辆</p>
                </a>
            </li>
            <li>
                <a href="<?php echo $this->createUrl("design/planother", array());?>&from=detail&tab=&order_id=<?php echo $_GET['order_id']?>">
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
        

        $('#switch').lc_switch();
        // triggered each time a field changes status
        $('body').delegate('.lcs_check', 'lcs-statuschange', function() {
        var status = ($(this).is(':checked')) ? 'checked' : 'unchecked';
        console.log('field changed status: '+ status );
        });

        // triggered each time a field is checked
        $('body').delegate('.lcs_check', 'lcs-on', function() {
            console.log('field is checked');
            $.post('<?php echo $this->createUrl("order/ChangeOrderStatus");?>',{order_id:<?php echo $_GET['order_id']; ?>,order_status:1},function(){
                alert("档期已预订");
            })
        });


        // triggered each time a is unchecked
        $('body').delegate('.lcs_check', 'lcs-off', function(){
            console.log('field is unchecked');
            $.post('<?php echo $this->createUrl("order/ChangeOrderStatus");?>',{order_id:<?php echo $_GET['order_id']; ?>,order_status:0},function(){
                alert("预订已取消");
            })
        });

        //已付款后，隐藏预定按钮
        var order_status = <?php echo $arr['order_status']; ?>;
        if(order_status > 1){
            $("#booking").remove();
        };

        //订单状态按钮，出事渲染
        var order_status = <?php echo $arr['order_status'];?>;
        if(order_status == 1){
            $('.lcs_checkbox_switch').removeClass('lcs_off');
            $('.lcs_checkbox_switch').addClass('lcs_on');
        }

        /*set_local();*/
        //order_id放入本地存储
        /*function set_local() {
            if ($.util.param("from") == "index" || $.util.param("from") == "my_order") {
                localStorage.setItem("order_id", $.util.param("order_id"));
                localStorage.setItem("from", $.util.param("from"));
            }
        }*/

        //点击返回，删除本地存储
        $(".abs_back_btn").on("click", function () {
            var from = localStorage.getItem("from");
            localStorage.removeItem("order_id");
            localStorage.removeItem("from");
            location.href = "<?php echo $this->createUrl('order/my', array());?>&t=plan&code=";
        })
    })
</script>
</body>
</html>
