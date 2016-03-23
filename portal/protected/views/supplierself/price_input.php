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
        <h2 class="page_title">收取现金</h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item" id="1">
                收取订金<i class="float_r t_green">&yen;10000</i>
            </li>
            <li class="ulist_item flex" id="2">
                收取中期款
                <div class="flex1">
                    <input id="middle" class="align_r" type="text" placeholder="输入金额"/>
                </div>
                <i class="mar_l10 t_green">元</i>
            </li>
            <li class="ulist_item flex" id="3">
                收取尾款
                <div class="flex1">
                    <input id="final" class="align_r" type="text" placeholder="输入金额"/>
                </div>
                <i class="mar_l10 t_green">元</i>
            </li>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
    	<a class="r_btn" id="sure">确认收款</a>
  	</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        
        //判断收款状态，渲染页面
        var deposit = "<?php echo $deposit ?>";
        var middle_section = "<?php echo $middle_section ?>"
        var retainage = "<?php echo $retainage; ?>"

        var html1 = '<div class="flex1"><input id="deposit" class="align_r" type="text" placeholder="输入金额"/></div><i class="mar_l10 t_green">元</i>';
        xuanran();

        //点击确认收款
        $("#sure").on("click",function(){
            var payment_data = getData();
            console.log(payment_data);
            $.post('<?php echo $this->createUrl("finance/indexdata");?>&order_id=<?php echo $_GET["order_id"]?>',payment_data,function(retval){
                alert(retval);
               // if(retval.success){
                location.href = "<?php echo $this->createUrl('finance/wedcostcalculate');?>&from=<?php echo $_GET['from']?>&order_id=<?php echo $_GET['order_id']?>";
               // }else{
               //   alert('太累了，歇一歇，一秒后再试试！');
                //     return false;
                //   }
            });
            
        })

        //点击返回
        $(".l_btn").on("click",function(){
            location.href = "<?php echo $this->createUrl('finance/wedcostcalculate');?>&from=<?php echo $_GET['from']?>&order_id=<?php echo $_GET['order_id']?>";
        })


        function xuanran(){
            if(deposit == ""){
                $("#1").find("i").addClass("hid");
                $("#1").append(html1).addClass("flex");
            }else if(middle_section == ""){
                var html2 = '收取订金<i class="float_r t_green">&yen;<span id="deposit1"><?php echo $deposit ?></span></i>';
                $("#1").empty();
                $("#1").append(html2).removeClass("flex");
            }else if(retainage == ""){
                var html2 = '收取订金<i class="float_r t_green">&yen;<span id="deposit1"><?php echo $deposit ?></span></i>';
                $("#1").empty();
                $("#1").append(html2).removeClass("flex");
                var html3 = '收取中期款<i class="float_r t_green">&yen;<span id="depmiddle_sectionosit1"><?php echo $middle_section ?></span></i>';
                $("#2").empty();
                $("#2").append(html3).removeClass("flex");
            }else {
                var html2 = '收取订金<i class="float_r t_green">&yen;<span id="deposit1"><?php echo $deposit ?></span></i>';
                $("#1").empty();
                $("#1").append(html2).removeClass("flex");
                var html3 = '收取中期款<i class="float_r t_green">&yen;<span id="middle_section1"><?php echo $middle_section ?></span></i>';
                $("#2").empty();
                $("#2").append(html3).removeClass("flex");
                var html4 = '收取尾款<i class="float_r t_green">&yen;<span id="retainage1"><?php echo $retainage ?></span></i>';
                $("#3").empty();
                $("#3").append(html4).removeClass("flex");
            }
        }

        function getData(){
            if(deposit == ""){
                payment_data={
                    'feast_deposit' : $("#deposit").attr("value") ,
                    'medium_term' : $("#middle").attr("value"),
                    'final_payments' : $("#final").attr("value")
                }
                return payment_data ;

            }else if(middle_section == ""){
                payment_data={
                    'feast_deposit' : $("#deposit1").html() ,
                    'medium_term' : $("#middle").attr("value"),
                    'final_payments' : $("#final").attr("value")
                }
                return payment_data ;

            }else if(retainage == ""){
                payment_data={
                    'feast_deposit' : $("#deposit1").html() ,
                    'medium_term' : $("#middle_section1").html() ,
                    'final_payments' : $("#final").attr("value")
                }
                return payment_data ;

            }else {
                payment_data={
                    'feast_deposit' : $("#deposit1").html() ,
                    'medium_term' : $("#middle_section1").html() ,
                    'final_payments' : $("#retainage1").html()
                }
                return payment_data ;

            }
        }
        

        
        

    })   
</script>
</body>
</html>
