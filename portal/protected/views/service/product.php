<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价管理</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article id="homepage">
    <div class="tool_bar fixed">
        <!-- <div class="l_btn" data-icon="&#xe69c;" id="filter"></div> -->
        <!-- <div class="l_btn" id="index" style='width:30%'>档期快查</div> -->
        <h2 class="page_title" id="pa_title">我的报价</h2>
    <div class="r_btn" data-icon="&#xe767;" style='width: 3.5rem;'></div>
    </div>
    <div class="ulist_module list-block" style='position: relative;top: 50px;'>
        <ul class="ulist order_list">
            <?php foreach ($arr_product as $key => $value) { ?>
                <li class="ulist_item swipeout" product-id="<?php echo $value['id']?>">
                    <div class="item-content">
                        <div class="order_info">
                            <p class="customer" style="margin-top:20px;margin-bottom:10px;font-size: 1.3rem;line-height: 1.2rem;white-space: nowrap;width: 180px;overflow: hidden;">
                                <?php echo $value['product_name']?>
                            </p>
                        </div>
                    </div>
                    <i class="name" service-type="<?php echo $value['service_type']?>"></i>
                </li>
            <?php } ?>
        </ul>
    </div>     
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script src="js/jquery.js"></script>
<script>
$(function () {
  //新增
  $(".r_btn").on("click",function(){
    location.href = "<?php echo $this->createUrl("service/product_add");?>&type=new&product_id=" + $(this).attr("product-id");
  })

  //编辑
  $("li").on("click",function(){
    location.href = "<?php echo $this->createUrl("service/product_add");?>&type=edit&product_id=" + $(this).attr("product-id");
  })
    
})

</script>
</body>
</html>