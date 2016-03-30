<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>收取现金</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="r_btn" data-icon="&#xe767;"></div>
        <h2 class="page_title">收取现金</h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
<?php 
    foreach ($payment as $key => $value) {
?>
            <li class="ulist_item" payment-id="<?php echo $value['id'];?>">
                <span payment-type='<?php echo $value['type']?>' class='type'>[0定金；1中期款；2尾款]</span><div style="display:inline-block;"><i class="way" payment-way="<?php echo $value['way']?>" style="display:inline-block;">[0现金；1公户；2支票]</i><i class="time" style="display:inline-block;">［<?php echo $value['time']?>］</i></div><i class="float_r t_green" style="display:inline-block;">&yen;<?php echo $value['money']?></i>
            </li>
<?php
    }
?>
        </ul>
    </div>

</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {

        //切换数字和文字
        for(var i=0; i<$(".ulist li").length; i++){
            var curr_li = $("li:eq("+i+")");
            var payment_type_no = curr_li.find(".type").attr("payment-type");
            var payment_way_no = curr_li.find(".way").attr("payment-way");  
            curr_li.find(".type").html(payment_type_json[payment_type_no].type_content);
            curr_li.find(".way").html(payment_way_json[payment_way_no].type_content);                  
        }


        //跳转到编辑
        $(".ulist_item").on("click",function(){
            location.href="<?php echo $this->createUrl("finance/cashier", array("order_id" => $_GET['order_id'],"type"=>'edit'));?>&paymentId=" + $(this).attr('payment-id');
        });

        //跳转到新增
        $(".r_btn").on("click",function(){
            location.href="<?php echo $this->createUrl("finance/cashier", array("order_id" => $_GET['order_id'],"type"=>'new'));?>&paymentId=";
        });
    })   
</script>
</body>
</html>
