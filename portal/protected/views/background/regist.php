<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>注册</title>
    <link rel="stylesheet" type="text/css" href="css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
</head>

<body>
    <!--header start-->
    <div class="regist_top">
        <div class="wapper clearfix">
            <div class="left">
                <h1 class="logo left"><a href="#"><img class="pngimg" src="images/logo.jpg" alt="" /></a></h1>
                <span class="slogan">注册xx账号</span>
            </div>

            <div class="right go_login">
                <a class="desc" href="">我已注册，现在就</a>
                <button id="back">登录</button>
            </div>
        </div>
    </div>
    <!--header end-->

    <!--con start-->
    <div class="regist_con wapper clearfix">
        <ul class="regist_ulist">
            <li>
                <label>手机：</label>
                <input class="inputItem" type="text" placeholder="默认" />
            </li>
            <li>
                <label>密码：</label>
                <input class="inputItem" type="password" placeholder="默认" />
            </li>
            <li>
                <label>短信验证码：</label>
                <input class="left inputItem short" type="text" placeholder="默认" />
                <span class="get_code">获取短信验证码</span>
            </li>
            <li class="agree_txt clearfix">
                <input class="left" type="checkbox">
                <p class="left">同意<span>《xx注册协议》</span>
                </p>
            </li>
            <li>
                <button class="registbtn">立即注册</button>
            </li>
        </ul>
    </div>
    <!--con end-->
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script>
    $(function  () {
        //返回登录页面
        $("#back").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/login");?>";
        });

    })
</script>
</body>

</html>