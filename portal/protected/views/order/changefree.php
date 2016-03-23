<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>免零</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<article style='position: relative;bottom: 200px;top: 1px;'>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">免零</h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item flex" id="2">
                免零
                <div class="flex1">
                    <input id="changefree" class="align_r" type="text" placeholder="输入免零金额，如 105"/>
                </div>
                <i class="mar_l10 t_green">元</i>
            </li>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
    	<a class="r_btn" id="sure">确认</a>
  	</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        
        //点击确认收款
        $("#sure").on("click",function(){

            data = {changefree:$("#changefree").val(),order_id:<?php echo $_GET['order_id']?>};
            console.log(data);
            $.post('<?php echo $this->createUrl("order/updatechangefree");?>',data,function(retval){
                //alert(retval);
               // if(retval.success){
                if("<?php echo $_GET['from1']?>" == 'meeting'){
                    location.href='<?php echo $this->createUrl("meeting/bill");?>&order_id=<?php echo $_GET["order_id"];?>&from=<?php echo $_GET["from"];?>';
                }else{
                    location.href='<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET["order_id"];?>&from=<?php echo $_GET["from"];?>';
                }
               // }else{
               //   alert('太累了，歇一歇，一秒后再试试！');
                //     return false;
                //   }
            });
        });
    })   
</script>
</body>
</html>
