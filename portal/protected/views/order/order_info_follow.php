<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单详情－跟进记录</title>
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
        <h2 class="page_title" id="pa_title">跟进记录</h2>
        <!--管理层显示该title -->
        <div class="r_btn" data-icon="&#xe767;"></div>
    </div>
    <ul class="m-index-list" id="page_list" style="position: relative;top: 20px;">
<?php 
    foreach ($follow as $key => $value) {
?>
        <li class="card " category="appear" status="进行中" followId='<?php echo $value['id']?>'>
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 1.8rem;text-align: left;margin-left: 10px;" mer-type="<?php echo $value['type']?>">[0进店面谈;1微信；2电话] <span style="color:#b6babf;font-size:1rem;"> [<?php echo $value['staff_name']?>] [<?php echo $value['time']?>]</span></h6>
            <div class="m-money clear" style="display:inline">
                <div style="margin-top:5px;margin-left:5%;color:#37CB58;font-size:1rem;">
                   [<?php echo $value['order_name']?> <?php echo $value['order_date']?>] 
                </div>
            </div>
            <div class="m-money clear" style="display:inline">
                <div style="display:inline-block;float:left;margin-left:5%;">
                   <?php echo $value['remarks']?>
                </div>
            </div>
        </li>
<?php } ?>
    </ul>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script>
    $(function(){

        //切换数字和文字
        for(var i=0; i<$(".m-index-list li").length; i++){
            var curr_li = $("li:eq("+i+")");
            var mer_type_no = curr_li.find(".m-list-tit").attr("mer-type"); 
            console.log(mer_type_no);
            curr_li.find(".m-list-tit").html(mer_type_json[mer_type_no].type_content);                  
        }

        $('.r_btn').on('click',function(){
            location.href='<?php echo $this->createUrl("order/orderinfofollowdetail");?>&type=new&order_id=<?php echo $_GET["order_id"]?>&followId=';
        });
        $('li').on('click',function(){
            location.href='<?php echo $this->createUrl("order/orderinfofollowdetail");?>&type=edit&order_id=<?php echo $_GET["order_id"]?>&followId=' + $(this).attr('followId');
        });
    });
</script>
</body>
</html>




