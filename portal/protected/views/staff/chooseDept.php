<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>选择部门</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">新增联系人</h2>
    </div>
    <div class="select_ulist_module pad_b50">
        <ul class="select_ulist">
            <?php foreach ($departments as $department) {
                if (!isset($selectedDepartment)) {
                    $selectedDepartment = $departments[0];
                }

                if ($selectedDepartment->id == $department->id) {
                    echo '<li class="select_ulist_item round_select round_select_selected" id="' . $department->id . '">' . $department->name . '</li>';
                } else {
                    echo '<li class="select_ulist_item round_select" id="' . $department->id . '">' . $department->name . '</li>';
                }
            } ?>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
        <a class="btn">确定</a>
    </div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/utility/util.js" type="text/javascript"></script>
<script>
    $(function () {
        //选择部门
        $(".select_ulist li").on("click", function () {
            if($(this).hasClass("round_select_selected")){
                $(this).removeClass("round_select_selected");
            }else{
                $(this).addClass("round_select_selected");
            };            
        });

        //点击确认
        $(".btn").on("click", function () {
            var dept = get_dept();
            location.href = "<?php echo $this->createUrl("staff/add");?>&choose_dept=" + escape(dept);
        });

        //点击返回
        $(".l_btn").on("click", function () {
            var u_info = $.parseJSON(localStorage.getItem("u_info"));
            var choosePosition = escape(u_info.department);
            location.href = "<?php echo $this->createUrl("staff/add");?>&choose_dept=" + choosePosition;
        });


        //获取用户选择了哪些部门
        function get_dept () {
            var dept = "";

            for (var i = 0; i < $(".select_ulist li").length; i++) {
                if($("li:eq("+i+")").hasClass("round_select_selected")){
                    dept += $("li:eq("+i+")").html()+"|";
                }
            };
            return dept;
        }
    });
</script>

</body>
</html>
