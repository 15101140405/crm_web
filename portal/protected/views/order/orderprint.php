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
    <div class="select_ulist_module" >
        <ul class="select_ulist" id="used_mail">
            <li class="select_ulist_item round_select" value=""></li>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
    	<a class="r_btn" id="print">确认</a>
  	</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/jquery.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        render_used_mail();
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
            var used_mail = jQuery.parseJSON(localStorage.getItem('used_mail'));
            // alert(used_mail);
            if(used_mail != null){
                var used_mail = jQuery.parseJSON(localStorage.getItem('used_mail'));
                used_mail.push({mail : $("#email").val()});
                localStorage.setItem('used_mail',JSON.stringify(used_mail));
            }else{
                used_mail = [
                    {mail : $("#email").val()},
                ]
                // alert(1);
                localStorage.setItem('used_mail',JSON.stringify(used_mail));
            }
            render_used_mail();
            console.log($("#email").val());
            console.log(used_mail);
            console.log(JSON.stringify(used_mail));
        });

        //选择常用邮箱
        function select_used_mail(){
            $(".select_ulist li").on("click", function () {
                if(!$(this).hasClass("round_select_selected")){
                    $("#used_mail li").removeClass("round_select_selected")
                    $(this).addClass("round_select_selected");
                    $("#email").val($(this).html());
                } else {
                    $("#used_mail li").removeClass("round_select_selected");
                    $(this).removeClass("round_select_selected");
                    $("#email").val($(this).html());
                }          
            });
        };
        
        

        //渲染常用邮箱
        function render_used_mail(){
            var used_mail = jQuery.parseJSON(localStorage.getItem('used_mail'));
            console.log(used_mail);
            var html = "";
            if(used_mail != null){
                $.each(used_mail,function(index,value){
                    html += '<li class="select_ulist_item round_select" value="">'+value.mail+'</li>';
                });
                $('#used_mail').empty();
                $('#used_mail').prepend(html);
                select_used_mail();
            };
        };

    })   
</script>
</body>
</html>
