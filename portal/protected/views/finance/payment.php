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
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">供应商［付款记录］</h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
           <li class="ulist_item flex" id="2">
                付款金额
                <div class="flex1">
                    <input id="money" class="align_r" type="text" placeholder="输入金额"/>
                </div>
                <i class="mar_l10 t_green">元</i>
            </li>
            <li class="ulist_item flex" id="2">
                <h4>备注</h4>
                <div class="flex1">
                    <textarea id='remarks' rows="2" cols="10" style="margin-top: 1rem;min-height:3rem;width:90%;margin-left:2.8rem"></textarea>
                </div>
            </li>
        </ul>
    </div>
    <div class="table_module " >
        <h4 class="module_title" style="font-size:1.4rem"><?php echo $_GET['supplier_name'];?>－付款记录 <i class="t_gray" style='float:right;font-size:1.1rem'>[未付] &yen;<?php echo $result['order_total']-$result['order_paid'];?></i></h4>
        <table class="mar_b10">
            <tbody>
    <?php 
        foreach ($payment as $key => $value) {
    ?>
            <tr>
                <tr>
                    <td style="font-size:1.2rem"><?php echo $value['update_time'];?></td>
                    <td class="t_green">&yen;<?php echo $value['money']; ?></td>
                </tr>
                <tr>
                    <td class="t_gray"><?php echo $value['remarks']; ?></td>
                </tr>
            </tr>
    <?php 
        }
    ?>
            </tbody>
        </table>
    </div>
    <div class="bottom_fixed_bar">
    	<a class="r_btn" id='sure'>确认付款</a>
  	</div>
</article>
<script src="js/zepto.min.js"></script>
<script>
    $("#sure").on("click",function  () {
        var mydate = new Date();
        var payment_data={
                    'order_id' : <?php echo $_GET['order_id'];?> ,
                    'supplier_id' : <?php echo $_GET['supplier_id'];?>,
                    'money' : $("#money").attr("value"),
                    'update_time' : mydate.toLocaleDateString(),
                    'remarks' :  $("#remarks").attr("value"),
            };
        console.log(payment_data);
        $.post("<?php echo $this->createUrl('finance/payinsert');?>",payment_data,function(){
            location.href = "<?php echo $this->createUrl('finance/wedcostcalculate');?>&from=<?php echo $_GET['from']?>&order_id=<?php echo $_GET['order_id']?>";
        });
    })

    $(".l_btn").on('click',function(){
        location.href = "<?php echo $this->createUrl('finance/wedcostcalculate');?>&from=<?php echo $_GET['from']?>&order_id=<?php echo $_GET['order_id']?>";
    })
</script>
</body>
</html>
