<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>新增员工</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="css/staff_management.css" rel="stylesheet" type="text/css"/>

</head>
<body>
<article>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">登录</h2>
    </div>
    <!-- <div class="avatar_upload">
        <p class="mar_b10">请设置头像、昵称，方便同事认出他/她</p>
        <div class="avatar">
            <input type="file" capture="camera"/>
        </div>
    </div> -->

    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item name">
                <span class="label" >手机号</span>
                <div class="int_bar">
                    <input class="align_r" id="phone" type="text" placeholder="请输入手机号"/>
                </div>
            </li>
        </ul>

    <div class="btn" id="insert">登录</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //点击新增
        $('#insert').on("click",function(){
            // alert($("#phone").val());
            $.post('<?php echo $this->createUrl("product/setuserid");?>',{phone:$("#phone").val()},function(data){
                alert(data);
                if(data=="success"){
                    location.reload();   
                }else{
                    alert("用户ID错误");
                };
            });
        });
    })
</script>
</body>
</html>