<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
</head>

<body>
    <div class="login_container">
        <div class="login_con">
            <div>
                <input class="loginItem" id="telephone" type="text" placeholder="手机号">
                <input class="loginItem pswd" id="password" type="password" placeholder="密码">
                <button class="login_btn" id="sure">登录</button>
                <a class="go_regist" href="#">立即注册</a>
            </div>
        </div>
        <!-- <div class="login_foot">
            <p>浩瀚一方</p>
            <p>@2016</p>
        </div> -->
    </div>
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/login.js"></script>
<script>
$(function(){
    $("#sure").on("click",function(){
        var data = {
            'telephone' : $("#telephone").val(),
            'password' : $("#password").val(),
        };
        $.post("<?php echo $this->createUrl("background/login_pro");?>",data,function(){
            location.href = "<?php echo $this->createUrl("background/index");?>";
        })
    })
})
</script>
</body>

</html>