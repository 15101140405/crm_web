<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>员工列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="css/calendar.css" rel="stylesheet" type="text/css"/>
    <script src="js/common.js"></script>
</head>
<body>
<article>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">员工列表</h2>
        <div class="r_btn" data-icon="&#xe767;"></div>
    </div>
    <!-- 搜索 -->
    <!-- <div class="search_module border_b">
        <input type="hidden" id="default_kwd" value="">
        <div class="search_bar">
            <p class="cancle">取消</p>
            <div class="search">
                <div class="search_btn" data-icon="&#xe65c;"></div>
                <div class="search_clear" data-icon="&#xe658;"></div>
                <div class="search_input_bar">
                    <input id="search_input" type="search" placeholder=""/>
                </div>
            </div>
        </div>
        <div class="search_show">
            未输入状态
            <div class="history">
                <ul class="u_list">
                </ul>
                <a class="clear_btn">清空搜索历史</a>
            </div>
            自动匹配状态 
            <div class="search_sug">
                <ul class="u_list">
                    <li class="keyword">输入的文字<span class="import" data-icon="&#xe767;"></span></li>
                </ul>
            </div>
        </div>
    </div>  -->
    <!-- 搜索 end -->

    <div class="contacts_ulist_module">
        <!-- loop -->
        <!-- <h4 class="contacts_index">A</h4> -->
            <ul class="contacts_ulist">
        <?php foreach ($arr_staff as $key => $value) {?>
                <li class="contacts_item" user-id="<?php echo $value['id'] ?>">
                    <div class="img_bar">
                        <img src="<?php echo $value['avatar'] ?>"/>
                    </div>
                    <div class="supplier_item_left">
                        <p class="remark"><?php echo $value['name'] ?></p>
                    </div>
                    <div class="supplier_item_right">
                <?php foreach ($value['department_list'] as $key1 => $value1) {?>
                        <p class="remark t_green" style="display:inline-block" depatment-id="<?php echo $value1 ?>"></p>
                <?php }?>
                    </div>
                </li>
        <?php } ?>
            </ul>
        <!-- loop end -->
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<!-- 搜索的交互方案待定 -->
<script src="js/search.js"></script>
<script>
    $(function () {
        //右上角增加按钮，进入add_usr
        $(".r_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("staff/add");?>&type=new&staff_id=&account_id=<?php echo $account_id ?>";
        });

        //点击li,进入编辑页面
        $(".contacts_item").on("click",function(){
            location.href = "<?php echo $this->createUrl('staff/add');?>&account_id=<?php echo $account_id?>&type=edit&staff_id=" + $(this).attr("user-id");
        });

        //渲染所属部门
        $("[depatment-id = '1']").html("统筹师");
        $("[depatment-id = '2']").html("策划师");
        $("[depatment-id = '3']").html("店长");
        $("[depatment-id = '5']").html("财务");
    });
</script>
</body>
</html>