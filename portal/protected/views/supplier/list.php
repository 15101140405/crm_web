<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>供应商列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="css/calendar.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">供应商列表</h2>

        <div class="r_btn" >新增</div>
    </div>
    <!-- 搜索-->
    <!--  <div class="search_module border_b">
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
     </div> -->
    <!-- 搜索 end -->
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <?php foreach ($arr as $value) { ?>
                <li class="int_ulist_item select_ulist_item1 round_select1 list_more" supplier-id="<?php echo $value['supplier_id']?>">
                    <div style='float:left;width:15%;z-index:999' class="select_click"></div>
                    <div style="float:right;width:85%;z-index:999" class="edit_click">
                        <span><?php echo $value['staff_name']; ?></span>
                        <div class="align_r supplier_type">[<?php echo $value['type_name']?>]</div>
                    <div>
                </li>
            <?php } ?>
        </ul>
    </div>
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="sure">确定</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //右上角增加按钮，进入add_usr
        $(".r_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("supplier/add");?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=<?php echo $_GET['supplier_id']?>&product_id=<?php echo $_GET['product_id']?>&edit_supplier_id=";
        });

        //点击某个供应商，进入add_supplier进行信息修改
        $(".edit_click").on("click", function () {
            var supplier_id = $(this).parent().attr("supplier-id");
            location.href = "<?php echo $this->createUrl("supplier/add");?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=<?php echo $_GET['supplier_id']?>&product_id=<?php echo $_GET['product_id']?>&edit_supplier_id="+supplier_id;
        });

        //选择部门
        $(".select_click").on("click", function () {
            $("li").removeClass("round_select_selected1");
            $(this).parent().addClass("round_select_selected1");
        });

        //确定
        $("#sure").on("click",function(){
            supplier_id = $(".round_select_selected1").attr("supplier-id");
            location.href = "<?php echo $this->createUrl("product/add");?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=" + supplier_id + "&product_id=<?php echo $_GET['product_id']?>";
        })
    });
</script>
</body>
</html>
