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
                <input class="inputItem" type="text" id="telephone" placeholder="请输入手机号" />
            </li>
            <li>
                <label>短信验证码：</label>
                <input class="left inputItem short" type="text" id="yzm" placeholder="请输入短信验证码" />
                <span class="get_code" id="get_code">获取短信验证码</span>
            </li>
            <li>
                <label>密码：</label>
                <input class="inputItem" type="password" id="password" placeholder="请输入密码" />
            </li>
            <!-- <li class="agree_txt clearfix">
                <input class="left" type="checkbox">
                <p class="left">同意<span>《xx注册协议》</span>
                </p>
            </li> -->
            <li>
                <button class="registbtn" id="regist">立即注册</button>
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

        var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/;
        //获取短信验证码
        $("#get_code").on("click",function(){

            if($("#telephone").val() == ""){
                alert("请输入手机号！");
            }else if($("#telephone").val().length != 11 || !myreg.test($("#telephone").val())){
                alert("请输入有效的手机号码！");
            }else {
                $.post("<?php echo $this->createUrl("background/register_pro");?>",{telephone : $("#telephone").val()},function(data){
                    if(data == "not exist"){
                        alert("您输入的手机号不存在！请与工作人员联系：15101140405");
                    };
                    alert(data);
                });
            };  
        });
        //立即注册
        $("#regist").on("click",function(){
            if($("#telephone").val() == ""){
                alert("请输入手机号！");
            }else if($("#yzm").val() == ""){
                alert("请输入短信验证码");
            }else if($("#password").val() == ""){
                alert("请输入密码！");
            }else if($("#telephone").val().length != 11 || !myreg.test($("#telephone").val())){
                alert("请输入有效的手机号！");
            }else {
                $.post("<?php echo $this->createUrl("background/register_pro");?>",{telephone : $("#telephone").val() , password : $("#password").val() , yzm : $("#yzm").val()},function(data){
                    if(data == "errow"){alert("您输入的验证码有误，请重新输入！");}
                    if(data == "success"){
                        data = {
                            telephone : $("#telephone").val(),
                            password : $("#password").val(),
                        };
                        $.post("<?php echo $this->createUrl("background/login_pro");?>",data,function(retval){
                            if(retval == "success"){
                                location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=2";    
                            };
                        });
                    };
                });
            }
        });
    })
</script>
</body>

</html>