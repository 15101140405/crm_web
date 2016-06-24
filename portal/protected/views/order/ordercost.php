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
        <h2 class="page_title"><?php if($_GET['from']=="wedding_feast"){echo "婚宴总支出";}else if($_GET['from']=="wedding"){echo "婚礼总支出";}else if($_GET['from']=="meeting_feast"){echo "会议餐总支出";}else if($_GET['from']=="meeting"){echo "会议其他总支出";}?></h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item flex">
                金额
                <div class="flex1">
                    <input id="cost" class="align_r" type="text" value="<?php if($_GET['money']!=0){echo $_GET['money'];}?>" placeholder="请输入金额"/>
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
            if($("#cost").val() == ""){
                alert("请输入金额！");
            }else{
                var data = {
                    'order_id' : '<?php echo $_GET['order_id']?>',
                    'type' : '<?php echo $_GET['from']?>',
                    'money' : $("#cost").val()
                };
                console.log(data);
                $.post("<?php echo $this->createUrl('order/savecost');?>",data,function(retval){
                    if(retval == "zero_error"){
                        alert(retval);
                    }else if(retval == 000){
                        alert('策划师还没录入商品，请您联系本单策划师/婚宴销售');
                <?php if($_GET['from'] == 'wedding_feast' || $_GET['from'] == 'wedding'){?>
                        location.href = "<?php echo $this->createUrl('design/bill');?>&order_id=<?php echo $_GET['order_id']?>&from=my_order";
                <?php }else if($_GET['from'] == 'meeting_feast' || $_GET['from'] == 'meeting'){?>
                        location.href = "<?php echo $this->createUrl('meeting/bill');?>&order_id=<?php echo $_GET['order_id']?>&from=my_order";
                <?php }?>
                    }else{
                        alert('保存成功！');
                        // alert(retval);
                <?php if($_GET['from'] == 'wedding_feast' || $_GET['from'] == 'wedding'){?>
                        location.href = "<?php echo $this->createUrl('design/bill');?>&order_id=<?php echo $_GET['order_id']?>&from=my_order";
                <?php }else if($_GET['from'] == 'meeting_feast' || $_GET['from'] == 'meeting'){?>
                        location.href = "<?php echo $this->createUrl('meeting/bill');?>&order_id=<?php echo $_GET['order_id']?>&from=my_order";
                <?php }?>
                    };
                });
            };
        });
    })   
</script>
</body>
</html>
