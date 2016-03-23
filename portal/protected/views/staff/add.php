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
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title"><?php echo (isset($action) && $action == "update") ? "修改联系人" : "新增联系人"; ?></h2>
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
                <span class="label">姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="例如“北风”" value="<?php echo $model->name?>"/>
                </div>
            </li>
            <li class="int_ulist_item phone">
                <span class="label">手机号</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="请填写手机号码" value="<?php echo $model->telephone?>"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more department">
                <span class="label">所在部门</span>
                <div class="align_r dep_content">
                    <?php echo $model->department_list;?>
                </div>
            </li>

            <li class="int_ulist_item position">
                <span class="label">所属门店</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder=""/>
                </div>
            </li>
        </ul>
    </div>
    <div class="btn">增加员工</div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {

        var getPosition = unescape($.util.param("choose_dept"));
        var user_id = unescape($.util.param("user_id"));

        //判断是否是从选择供应商页面进入，如果是，则将之前填写的值写入
        if (getPosition != "null") {
            var u_info = $.parseJSON(localStorage.getItem("u_info"));
            $(".avatar").css("background", u_info.img);
            $(".name input").val(u_info.na);
            $(".phone input").val(u_info.phone);
            $(".position input").val(u_info.position);
            $(".department .dep_content").html(getPosition);

        } else if (user_id != "null") {//判断是否点击某个员工进入
            //获取此员工信息
            $.getJSON('#', {userId: user_id}, function (retval) {
                if (retval.success) {

                    //后台获取到 用户头像，用户姓名，电话，职位，部门
                    $(".avatar").css("background", "");
                    $(".name input").val(retval.na);
                    $(".phone input").val(retval.phone);
                    $(".position input").val(retval.position);
                    $(".department .dep_content").html(retval.dept);
                } else {
                    alert('太累了，歇一歇，一秒后再试试！');
                    return false;
                }
            });
        }

        //点击左上角返回按钮
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("staff/list");?>";
            localStorage.removeItem(u_info);
        });

        //点击添加用户头像
        $(".avatar").on("click", function () {
            console.log($(".avatar input").val());
        });

        //部门选择
        $(".department").on("click", function () {
            var u_info = get_user_info();
            //用户所填写内容暂时存入本地
            localStorage.setItem("u_info", JSON.stringify(u_info));
            location.href = "<?php echo $this->createUrl("staff/chooseDept");?>";
        })

        //点击确认按钮提交用户信息
        $(".btn").on("click", function () {
            var u_info = get_user_info();
            //判断信息是否完整
            if (u_info.na == "" || u_info.phone == "" || u_info.department == "" || u_info.position == "") {
                alert("请补全信息");
                return false;
            }
            else if (!$.regex.isPhone(u_info.phone)) {   //phone number correct or not
                alert("请输入正确的手机号！");
                return false;
            }

            //提交用户信息
            //$.postJSON('#',u_info,function(retval){
            // if(retval.success){
            location.href = "<?php echo $this->createUrl("staff/list");?>";
            //  }else{
            //    alert('太累了，歇一歇，一秒后再试试！');
            //    return false;
            //  }
        });

        localStorage.removeItem(u_info);

        /* ===========================
         * 获取界面填写的员工信息
         * =========================== */
        function get_user_info() {
            var u_info = {
                img: $(".avatar").css("background"),
                na: $(".name input").val(),
                phone: $(".phone input").val(),
                department: $(".department .dep_content").html(),
                position: $(".position input").val()
            }

            return u_info;
        }
    })
</script>
</body>
</html>