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
                <a class="go_regist" href="<?php echo $this->createUrl("background/regist");?>" style="float: right;font-size: 1rem;margin-top: 20px;">立即注册</a>
                <a class="go_regist" href="" style="float: left;margin-left: 15px;font-size: 1rem;margin-top: 20px;">忘记密码</a>
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
        if(data.telephone == ""){
            alert("请输入您的手机号！");
        }else if(data.password == ""){
            alert("请输入密码！");
        }else{
            $.post("<?php echo $this->createUrl("background/login_pro");?>",data,function(retval){
                /*alert(retval);*/
                if(retval == "success"){
                    var department_list = "<?php if(isset($_COOKIE['department_list'])){echo $_COOKIE['department_list'];}?>";
                    department_list = department_list.substring(0,department_list.length-1);
                    department_list = department_list.substring(1);
                    var list = new Array();
                    list = department_list.split(',');
                    var t=1;
                    var j=0;
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
                    console.log(department_list);
                    console.log(list);
                    console.log(t);
                    console.log(j);
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
                    }
                }else if(retval == "not exist"){
                    alert("您输入的手机号不存在！");
                }else if(retval == "password error"){
                    alert("您输入的密码错误！");
                }
            });
        };
    });
})
</script>
</body>

</html>