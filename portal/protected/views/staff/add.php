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
        <h2 class="page_title">新增员工</h2>
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
                <span class="label" >姓名</span>
                <div class="int_bar">
                    <input class="align_r" id="name" type="text" placeholder="请输入员工姓名" />
                </div>
            </li>
            <li class="int_ulist_item phone">
                <span class="label" >手机号</span>
                <div class="int_bar">
                    <input class="align_r" id="phone" type="text" placeholder="请填写手机号码" />
                </div>
            </li>
        </ul>
    </div>
    <div class="select_ulist_module pad_b50">
        <ul class="select_ulist" id="department">
            <li class="select_ulist_item round_select round_select_selected" department-value="1" id="plan">统筹师(销售)</li>
            <li class="select_ulist_item round_select" department-value="2" id="design">策划师</li>
            <li class="select_ulist_item round_select" department-value="3" id="manager">店长</li>
            <li class="select_ulist_item round_select" department-value="5" id="finance">财务</li>
        </ul>
    </div>
    <div class="select_ulist_module pad_b50">
        <ul class="select_ulist" id="gender">
            <li class="select_ulist_item round_select round_select_selected" gender-value="1" id="plan">男</li>
            <li class="select_ulist_item round_select" gender-value="2" id="design">女</li>
        </ul>
    </div>
    <div class="select_ulist_module pad_b50">
        <ul class="select_ulist" id="hotel">
<?php foreach ($arr_hotel as $key => $value) {?>
            <li class="select_ulist_item round_select " hotel-value="<?php echo $value['id']?>"><?php echo $value['name']?></li>
<?php }?>
        </ul>
    </div>

    <div class="btn" id="insert">增加员工</div>
    <div class="btn" id="update">确定</div>
    <div class="btn del" id="del">删除</div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //页面初始渲染
        var type = '<?php echo $_GET['type']?>';
        if(type == 'new'){
            $("#update").remove();
            $("#del").remove();
        }else if(type == 'edit'){
            $("#insert").remove();
            $(".page_title").html("修改员工信息");
            $("#name").val("<?php echo $arr_staff['name']?>");
            $("#phone").val("<?php echo $arr_staff['phone']?>");
            $("li").removeClass("round_select_selected");
<?php   foreach ($arr_staff['department_list'] as $key => $value) { ?>
            $("[department-value = '<?php echo $value?>']").addClass("round_select_selected");
<?php   }  ?>
            $("[hotel-value = '<?php echo $arr_staff['hotel_list']?>']").addClass("round_select_selected");
        };

        

        //点击新增
        $('#insert').on("click",function(){
            var department_list = "";
            
            //构造职位列表
            if($("#plan").hasClass("round_select_selected")){department_list += $("#plan").attr('department-value');department_list += ","};
            if($("#design").hasClass("round_select_selected")){department_list += $("#design").attr('department-value');department_list += ","};
            if($("#manager").hasClass("round_select_selected")){department_list += $("#manager").attr('department-value');department_list += ","};
            if($("#finance").hasClass("round_select_selected")){department_list += $("#finance").attr('department-value');department_list += ","};

            department_list = department_list.substring(0,department_list.length - 1);

            if ($("#name").val() == "" || $("#phone").val() == "" || $("#hotel li .round_select_selected").attr("hotel-value") || department_list == "[]") {
                alert("请补全信息");
                return false;
            }
            else if (!$.regex.isPhone($("#phone").val())) {   //phone number correct or not
                alert("请输入正确的手机号！");
                return false;
            }
            

            data = {
                account_id : <?php echo $_GET['account_id']?>,
                name : $("#name").val(),
                telephone : $("#phone").val(),
                department_list : department_list,
                hotel_list : $("#hotel li.round_select_selected").attr("hotel-value"),
                gender : $("#gender li.round_select_selected").attr("gender-value")
            };
            console.log(data);
            $.post('<?php echo $this->createUrl("staff/insert");?>',data,function(data){
                alert(data);
                //location.href = "<?php echo $this->createUrl("staff/list");?>&code=&account_id=<?php echo $_GET['account_id']?>sa";
            })
        });

        //点击编辑
        $('#update').on("click",function(){
            var department_list = "[";
            
            //构造职位列表
            if($("#plan").hasClass("round_select_selected")){department_list += $("#plan").attr('department-value');department_list += ","};
            if($("#design").hasClass("round_select_selected")){department_list += $("#design").attr('department-value');department_list += ","};
            if($("#manager").hasClass("round_select_selected")){department_list += $("#manager").attr('department-value');department_list += ","};
            if($("#finance").hasClass("round_select_selected")){department_list += $("#finance").attr('department-value');department_list += ","};

            department_list = department_list.substring(0,department_list.length - 1);

            department_list += "]";

            if ($("#name").val() == "" || $("#phone").val() == "" || $("#hotel li .round_select_selected").attr("hotel-value") || department_list == "[]") {
                alert("请补全信息");
                return false;
            }
            else if (!$.regex.isPhone($("#phone").val())) {   //phone number correct or not
                alert("请输入正确的手机号！");
                return false;
            }

            data = {
                staff_id : "<?php echo $_GET['staff_id'];?>",
                name : $("#name").val(),
                telephone : $("#phone").val(),
                department_list : department_list,
                hotel_list : $("#hotel li.round_select_selected").attr("hotel-value")
            };

            $.post('<?php echo $this->createUrl("staff/update");?>',data,function(){
                location.href = "<?php echo $this->createUrl("staff/list");?>&code=&account_id=<?php echo $_GET['account_id']?>";
            });
        });

        //删除
        $('#del').on("click",function(){
            data = {
                staff_id : "<?php echo $_GET['staff_id'];?>",
            };

            $.post('<?php echo $this->createUrl("staff/del");?>',data,function(){
                location.href = "<?php echo $this->createUrl("staff/list");?>&code=&account_id=<?php echo $_GET['account_id']?>";
            });
        });
        

        //点击左上角返回按钮
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("staff/list");?>";
            localStorage.removeItem(u_info);
        });

        //选择部门
        $("#department li").on("click", function () {
            if($(this).hasClass("round_select_selected")){
                $(this).removeClass("round_select_selected");
            }else{
                $(this).addClass("round_select_selected");
            };            
        });

        //选择门店
        $("#hotel li").on("click", function () {
            $("#hotel li").removeClass("round_select_selected")
            $(this).addClass("round_select_selected");
        });

    })
</script>
</body>
</html>