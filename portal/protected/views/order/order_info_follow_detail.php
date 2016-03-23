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
        <h2 class="page_title">跟进记录</h2>
    </div>
    <div class="select_ulist_module" id="way">
        <ul class="select_ulist">
            <li class="ulist_item">记录内容</li> 
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="请填写记录内容" id="remark"></textarea>
                </div>
            </li> 
            <li class="select_ulist_item round_select round_select_selected" value="0">进店面谈</li>
            <li class="select_ulist_item round_select" value="1">打电话</li>
            <li class="select_ulist_item round_select" value="2">发微信</li>
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

            

        //选择收款方式
        $(".select_ulist li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $(".select_ulist li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            }           
        });



    

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
