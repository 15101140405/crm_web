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
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">收取现金</h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item" id="1">
                收取订金<div id='a'><i class="way"><?php echo $deposit_way?></i><i class="time"><?php echo $deposit_time?></i></div><i class="float_r t_green">&yen;10000</i>
            </li>
            <li class="ulist_item flex" id="2">
                收取中期款<div id='b'><i class="way"><?php echo $middle_section_way?></i><i class="time"><?php echo $middle_section_time?></i></div>
                <div class="flex1">
                    <input id="middle" class="align_r" type="text" placeholder="输入金额"/>
                </div>
                <i class="mar_l10 t_green">元</i>
            </li>
            <li class="ulist_item flex" id="3">
                收取尾款<div id='c'><i class="way"><?php echo $retainage_way?></i><i class="time"><?php echo $retainage_time?></i></div>
                <div class="flex1">
                    <input id="final" class="align_r" type="text" placeholder="输入金额"/>
                </div>
                <i class="mar_l10 t_green">元</i>
            </li>
        </ul>
    </div>
    <div class="select_ulist_module" id="way">
        <ul class="select_ulist">
            <li class="select_ulist_item round_select round_select_selected" value="0">现金</li>
            <li class="select_ulist_item round_select" value="1">公户</li>
            <li class="select_ulist_item round_select" value="2">支票</li>
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
        var middle_section = "<?php echo $middle_section ?>";
        var retainage = "<?php echo $retainage; ?>";
        var order_status = <?php echo $order_status; ?>;

        var html1 = '<div class="flex1"><input id="deposit" class="align_r" type="text" placeholder="输入金额"/></div><i class="mar_l10 t_green">元</i>';
        xuanran();

        //判断收款方式，渲染页面
        var way1 = $("#a .way").html();
        if(way1 == 0){
            $("#a").find(".way").html("[现金]");
        }else if(way1 == 1){
            $("#a").find(".way").html("[公户]");
        }else if(way1 == 2){
            $("#a").find(".way").html("[支票]");
        }            
        
        var way2 = $("#b .way").html();
        if(way2 == 0){
            $("#b").find(".way").html("[现金]");
        }else if(way2 == 1){
            $("#b").find(".way").html("[公户]");
        }else if(way2 == 2){
            $("#b").find(".way").html("[支票]");
        } 

        var way3 = $("#c .way").html();
        if(way3 == 0){
            $("#c").find(".way").html("[现金]");
        }else if(way3 == 1){
            $("#c").find(".way").html("[公户]");
        }else if(way3 == 2){
            $("#c").find(".way").html("[支票]");
        } 
        
        

        //点击确认收款
        $("#sure").on("click",function(){
            var mydate = new Date();
            var year = mydate.getFullYear() + "";
            var month = mydate.getMonth() + 1;
            var month = month + "";
            var date = mydate.getDate() + "";
            var hours = mydate.getHours() + "";
            var minutes = mydate.getMinutes() + "";
            var seconds = mydate.getSeconds() + "";

            var time = year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds;

            var payment_data = getData();
            data = {payment:payment_data,payment_time:time,payment_way:$('.round_select_selected').val(),order_status:<?php echo $order_status;?>,order_id:<?php echo $_GET["order_id"]?>};
            console.log(data);
            $.post('<?php echo $this->createUrl("finance/indexdata");?>',data,function(retval){
                //alert(retval);
               // if(retval.success){
                location.reload();
               // }else{
               //   alert('太累了，歇一歇，一秒后再试试！');
                //     return false;
                //   }
            });
        });

        //选择收款方式
        $(".select_ulist li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $(".select_ulist li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            }           
        });



        function xuanran(){
            if(order_status == 0 || order_status == 1){
                $("#1").find("i").addClass("hid");
                $("#1").append(html1).addClass("flex");
                $("#2").remove();
                $("#3").remove();
            }else if(order_status == 2){
                $("#3").remove();
                var html2 = '收取订金<div style="display:inline-block" id="a"><i class="way"><?php echo $deposit_way?></i><i class="time"><?php echo $deposit_time?></i></div><i class="float_r t_green"><span id="deposit1"><?php echo $deposit ?>元</span></i>';
                $("#1").empty();
                $("#1").append(html2).removeClass("flex");
            }else if(order_status == 3){
                var html2 = '收取订金<div style="display:inline-block" id="a"><i class="way"><?php echo $deposit_way?></i><i class="time"><?php echo $deposit_time?></i></div><i class="float_r t_green">&yen;<span id="deposit1"><?php echo $deposit ?></span></i>';
                $("#1").empty();
                $("#1").append(html2).removeClass("flex");
                var html3 = '收取中期款<div style="display:inline-block" id="b"><i class="way"><?php echo $middle_section_way?></i><i class="time"><?php echo $middle_section_time?></i></div><i class="float_r t_green">&yen;<span id="depmiddle_sectionosit1"><?php echo $middle_section ?></span></i>';
                $("#2").empty();
                $("#2").append(html3).removeClass("flex");
            }else {
                $("#sure").remove();
                $("#way").remove();
                var html2 = '收取订金<div style="display:inline-block" id="a"><i class="way"><?php echo $deposit_way?></i><i class="time"><?php echo $deposit_time?></i></div><i class="float_r t_green">&yen;<span id="deposit1"><?php echo $deposit ?></span></i>';
                $("#1").empty();
                $("#1").append(html2).removeClass("flex");
                var html3 = '收取中期款<div style="display:inline-block" id="b"><i class="way"><?php echo $middle_section_way?></i><i class="time"><?php echo $middle_section_time?></i></div><i class="float_r t_green">&yen;<span id="middle_section1"><?php echo $middle_section ?></span></i>';
                $("#2").empty();
                $("#2").append(html3).removeClass("flex");
                var html4 = '收取尾款<div style="display:inline-block" id="c"><i class="way"><?php echo $retainage_way?></i><i class="time"><?php echo $retainage_time?></i></div><i class="float_r t_green">&yen;<span id="retainage1"><?php echo $retainage ?></span></i>';
                $("#3").empty();
                $("#3").append(html4).removeClass("flex");
            }
        }

        function getData(){

            if(order_status == 0 || order_status == 1){
                payment_data=payment=$("#deposit").attr("value");
                return payment_data ;

            }else if(order_status == 2){
                payment_data=$("#middle").attr("value");
                return payment_data ;

            }else if(order_status ==3){
                payment_data=$("#final").attr("value");
                return payment_data ;
            }
        }
    })   
</script>
</body>
</html>
