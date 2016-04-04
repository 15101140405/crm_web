<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>报价单－打印</title>
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
        <h2 class="page_title">报价单－打印</h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item flex" id="2">
                请输入邮箱地址
                <div class="flex1">
                    <input id="email" class="align_r" type="text" placeholder="例如：abc@***.***"/>
                </div>
            </li>
        </ul>
    </div>
    <p class="t_green">长时间未收到邮件，则邮件可能已被拦截，请到邮箱的“垃圾邮件”中查找</p>
    <div class="bottom_fixed_bar">
    	<a class="r_btn" id="print">确认</a>
  	</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        
        //打印
            $("#print").on("click",function(){
                if('<?php echo $_GET['type']?>' == "design"){
                    $.post('<?php echo $this->createUrl("print/designbill")?>',{'email' : $("#email").val(),'order_id' : <?php echo $_GET['order_id']?>},function(revtal){
                        //alert(revtal);
                        alert('报价单已发送至邮箱，请查收！');
                    })
                }else if('<?php echo $_GET['type']?>' == "meeting"){
                    $.post('<?php echo $this->createUrl("print/meetingbill")?>',{'email' : $("#email").val(),'order_id' : <?php echo $_GET['order_id']?>},function(revtal){
                        //alert(revtal);
                        alert('报价单已发送至邮箱，请查收！');
                    })
                }
                    
                
            });

    })   
</script>
</body>
</html>
