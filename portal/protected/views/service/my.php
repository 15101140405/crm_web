<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单列表－我的订单</title>
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
<article id="homepage">
    <div class="tool_bar fixed">
        <!-- <div class="l_btn" data-icon="&#xe69c;" id="filter"></div> -->
        <div class="l_btn" id="index" style='width:30%'>档期快查</div>
        <h2 class="page_title" id="pa_title">我的订单</h2>
    <div class="r_btn" data-icon="&#xe767;" style='width: 4.5rem;'></div>
    </div>
    <div class="ulist_module list-block" style='position: relative;top: 0px;'>
        <ul class="ulist order_list">
            <?php foreach ($arr_order as $key => $value) { ?>
                <li class="ulist_item swipeout" order-id="<?php echo $value['id']?>" service-type="<?php echo $value['service_type']?>">
                    <div class="item-content">
                        <span class="order_status  status_green" style="margin-top: 15px;margin-bottom: 15;font-size:1.3rem;" order-status="<?php echo $value['order_status']?>">
                            未执行
                        </span>
                        <div class="order_info" order-id="<?php echo $value['id']?>" service-type="<?php echo $value['service_type']?>" order-status="<?php echo $value['order_status']?>">
                            <p class="customer" style="margin-top:20px;margin-bottom:10px;font-size: 1.3rem;line-height: 1.2rem;white-space: nowrap;width: 180px;overflow: hidden;">
                                <?php echo $value['order_name']?>
                            </p>
                            <p class="desc" style="margin-top: 8px;margin-bottom: 0;font-size:1rem;">
                                <?php echo $value['order_date']?>
                            </p>
                        </div>
                        <div class="swipeout-actions-right" id="del">
                            <a class="swipeout-delete del">删除订单</a>
                        </div>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>     
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script src="js/jquery.js"></script>
<script src="js/lc_switch.js" type="text/javascript"></script>


<script type="text/javascript" src="js/framework7.min.js"></script>
<script type="text/javascript" src="js/upscroller.js"></script>
<script type="text/javascript" src="js/my-app.js"></script>
<script>
$(function () {
    //新增
    $(".r_btn").on("click",function(){
        location.href = "<?php echo $this->createUrl("service/create_order");?>&type=new&service_order_id=&service_team_id=<?php echo $_SESSION['service_team_id']?>";
    });

    //编辑
    $("li").on("click",function(){
        location.href = "<?php echo $this->createUrl("service/create_order");?>&type=edit&service_team_id=<?php echo $_SESSION['service_team_id']?>&service_order_id="+ $(this).attr("order-id");
    });

    //删除
    $(".del").on("click",function(){
        $("li").unbind("click");
        $.post("<?php echo $this->createUrl("service/del_order");?>",{service_order_id:$(this).parent().parent().parent().attr("order-id")},function(){
            location.reload(); 
        });
    });

    //档期快查
    $("#index").on("click",function(){
        location.href = "<?php echo $this->createUrl("service/index");?>&code=&service_team_id=<?php echo $_SESSION['service_team_id']?>&from=&supplier_product_id=";
    });

})

</script>
</body>
</html>