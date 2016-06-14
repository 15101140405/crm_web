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
                <h1 class="logo left"><a href="#"><img class="pngimg" style="width:auto;margin-top:0;" src="images/logo_black.png" alt="" /></a></h1>
                <span class="slogan">注册账号</span>
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
        <div class="regist_step">
            <span><img id="progress" src="images/cjtubiao_03.png" alt=""></span>
            <span style="display:none;" ><img src="" alt=""></span>
        </div>
        <div class="regist_step_con">
            <div class="regist_step_item">
                <p class="tit">请选择您的身份，以便我们为您提供更针对的服务，获取更多商业机会</p>
                <div class="sele_box clearfix" id="role">
                    <div class="seled_item left" id="desigener">
                        <img id="designer" class="active" src="images/cjtubiao_09.png" alt="">
                        <p class="name">策划师</p>
                    </div>
                    <ul class="sele_list left" >
                        <li><img id="host" department="11" src="images/cjtubiao_11.png" alt=""><p class="name">主持人</p></li>
                        <li><img id="video" department="12" src="images/cjtubiao_17.png" alt=""><p class="name">摄像师</p></li>
                        <li><img id="camera" department="13" src="images/cjtubiao_15.png" alt=""><p class="name">摄影师</p></li>
                        <li><img id="makeup" department="14" src="images/cjtubiao_13.png" alt=""><p class="name">化妆师</p></li>
                        <li><img id="decoration" department="15" src="images/cjtubiao_13.png" alt=""><p class="name">场地布置</p></li>
                        <li><img id="lighting" department="16" src="images/cjtubiao_13.png" alt=""><p class="name">灯光</p></li>
                        <li><img id="sound" department="17" src="images/cjtubiao_13.png" alt=""><p class="name">音响</p></li>
                        <li><img id="shipin" department="18" src="images/cjtubiao_13.png" alt=""><p class="name">视频</p></li>
                    </ul>
                </div>
                <button class="registbtn registbtn_next">下一步</button>
            </div>
            <ul class="regist_ulist disigner regist_step_item">
                <li>
                    <label>手机：</label>
                    <input class="inputItem" type="text" id="designer_telephone" placeholder="请输入手机号" />
                </li>
                <li>
                    <label>短信验证码：</label>
                    <input class="left inputItem short" type="text" id="designer_yzm" placeholder="请输入短信验证码" />
                    <span class="get_code" id="designer_get_code">获取短信验证码</span>
                </li>
                <li>
                    <label>密码：</label>
                    <input class="inputItem" type="password" id="designer_password" placeholder="请输入密码" />
                </li>
                <li>
                    <button class="registbtn" id="designer_regist">立即注册</button>
                </li>
            </ul>
            <ul class="regist_ulist service regist_step_item">
                <li>
                    <label>姓名：</label>
                    <input class="inputItem" type="text" id="service_name" placeholder="请输入您的姓名" />
                </li>
                <li>
                    <label>手机：</label>
                    <input class="inputItem" type="text" id="service_telephone" placeholder="请输入手机号" />
                </li>
                <li>
                    <label>短信验证码：</label>
                    <input class="left inputItem short" type="text" id="service_yzm" placeholder="请输入短信验证码" />
                    <span class="get_code" id="service_get_code">获取短信验证码</span>
                </li>
                <li>
                    <label>密码：</label>
                    <input class="inputItem" type="password" id="service_password" placeholder="请输入密码" />
                </li>
                <li>
                    <button class="registbtn" id="service_regist">立即注册</button>
                </li>
            </ul>
        </div>

    </div>
    <!--con end-->
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script>
        $(function(){
            $(".registbtn_next").on('click',function(){
                if($("#designer").hasClass("active")){
                    $(".disigner").css('display','inline-block').siblings().hide();    
                }else if($("#host").hasClass("active") || $("#video").hasClass("active") || $("#camera").hasClass("active") ||$("#makeup").hasClass("active") ||$("#decoration").hasClass("active") ||$("#lighting").hasClass("active") ||$("#sound").hasClass("active") ||$("#shipin").hasClass("active")){
                    $(".service").css('display','inline-block').siblings().hide();
                };
                $("#progress").attr("src","images/cjtubiao_06.png")
            });
            var myreg = /^(((13[0-9]{1})|(14[0-9]{1})|(17[0]{1})|(15[0-3]{1})|(15[5-9]{1})|(18[0-9]{1}))+\d{8})$/;
            //返回登录页面
            $("#back").on("click",function(){
                location.href = "<?php echo $this->createUrl("background/login");?>";
            });

            //选择角色
            $("#role img").on("click",function(){
                $("#role img").removeClass("active");
                $(this).addClass("active");
            });

            //获取验证码
            $("#designer_get_code").on("click",function(){
                console.log($("#disigner_telephone").val());
                if($("#disigner_telephone").val() == ""){
                    alert("请输入手机号！");
                }else if($("#disigner_telephone").val().length != 11 || !myreg.test($("#disigner_telephone").val())){
                    alert("请输入有效的手机号码！");
                }else {
                    $.post("<?php echo $this->createUrl("background/register_pro");?>",{telephone : $("#disigner_telephone").val()},function(data){
                        if(data == "not exist"){
                            alert("您输入的手机号不存在！请与工作人员联系：15101140405");
                        };
                    });
                };  
            });
            $("#service_get_code").on("click",function(){
                if($("#service_telephone").val() == ""){
                    alert("请输入手机号！");
                }else if($("#service_telephone").val().length != 11 || !myreg.test($("#service_telephone").val())){
                    alert("请输入有效的手机号码！");
                }else {
                    $.post("<?php echo $this->createUrl("background/register_host_pro");?>",{telephone : $("#service_telephone").val() , department : $("#role").find(".active").attr("department")},function(data){
                        if(data == "not exist"){
                            alert("您输入的手机号不存在！请与工作人员联系：15101140405");
                        };
                        alert(data);
                    });
                }; 
            });

            //点击立即注册
            $("#designer_regist").on("click",function(){
                if($("#disigner_telephone").val() == ""){
                    alert("请输入手机号！");
                }else if($("#designer_yzm").val() == ""){
                    alert("请输入短信验证码");
                }else if($("#designer_password").val() == ""){
                    alert("请输入密码！");
                }else if($("#disigner_telephone").val().length != 11 || !myreg.test($("#disigner_telephone").val())){
                    alert("请输入有效的手机号！");
                }else {
                    $.post("<?php echo $this->createUrl("background/register_pro");?>",{telephone : $("#disigner_telephone").val() , password : $("#designer_password").val() , yzm : $("#designer_yzm").val()},function(data){
                        if(data == "errow"){alert("您输入的验证码有误，请重新输入！");}
                        if(data == "success"){
                            data = {
                                telephone : $("#disigner_telephone").val(),
                                password : $("#designer_password").val(),
                            };
                            $.post("<?php echo $this->createUrl("background/login_pro");?>",data,function(retval){
                                if(retval == "success"){
                                    var department_list = "<?php if(isset($_COOKIE['department_list'])){echo $_COOKIE['department_list'];}?>";
                                    department_list = department_list.substring(0,department_list.length-1);
                                    department_list = department_list.substring(1);
                                    var list = new Array();
                                    list = department_list.split(',');
                                    var t=0;
                                    for (i=0;i<list.length;i++) {
                                        if(list[i] == 11){t++};
                                    };
                                    if(t=0){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=2";
                                    }else{
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=6";
                                    };     
                                };
                            });
                        };
                    });
                }
            });

            $("#service_regist").on("click",function(){
                if($("#service_telephone").val() == ""){
                    alert("请输入手机号！");
                }else if($("#service_yzm").val() == ""){
                    alert("请输入短信验证码");
                }else if($("#service_password").val() == ""){
                    alert("请输入密码！");
                }else if($("#service_telephone").val().length != 11 || !myreg.test($("#service_telephone").val())){
                    alert("请输入有效的手机号！");
                }else {
                    $.post("<?php echo $this->createUrl("background/register_host_pro");?>",{telephone : $("#service_telephone").val() , password : $("#service_password").val() , yzm : $("#service_yzm").val() , name :$("#service_name").val() , department : $("#role").find(".active").attr("department")},function(data){
                        if(data == "errow"){alert("您输入的验证码有误，请重新输入！");}
                        if(data == "success"){
                            data = {
                                telephone : $("#service_telephone").val(),
                                password : $("#service_password").val(),
                            };
                            $.post("<?php echo $this->createUrl("background/login_pro");?>",data,function(retval){
                                if(retval == "success"){
                                    var department_list = "<?php if(isset($_COOKIE['department_list'])){echo $_COOKIE['department_list'];}?>";
                                    department_list = department_list.substring(0,department_list.length-1);
                                    department_list = department_list.substring(1);
                                    var list = new Array();
                                    list = department_list.split(',');
                                    var t=0;
                                    for (i=0;i<list.length;i++) {
                                        if(list[j] == 0){t=0;j++;};
                                        if(list[j] == 11){t=11;j++;};
                                        if(list[j] == 12){t=12;j++;};
                                        if(list[j] == 13){t=13;j++;};
                                        if(list[j] == 14){t=14;j++;};
                                        if(list[j] == 15){t=15;j++;};
                                        if(list[j] == 16){t=16;j++;};
                                        if(list[j] == 17){t=17;j++;};
                                        if(list[j] == 18){t=18;j++;};
                                    };
                                    if(t==1){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=2";
                                    }else if(t==11){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=6";
                                    }else if(t==12){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=13";
                                    }else if(t==13){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=14";
                                    }else if(t==14){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=15";
                                    }else if(t==0){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=1";
                                    }else if(t==15){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=17";
                                    }else if(t==16){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=18";
                                    }else if(t==17){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=19";
                                    }else if(t==18){
                                        location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=20";
                                    };    
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