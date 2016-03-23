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

        <div class="r_btn" data-icon="&#xe767;"></div>
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

    <div class="contacts_ulist_module">
        <!-- loop -->
        <?php
        //background data
//        $arr = array(
//
//            '0' => array('supplier_id' => '001',
//                'staff_name' => '吴辉',
//                'name' => '婚礼主持1' //type
//            ),
//            '1' => array('supplier_id' => '003',
//                'staff_name' => '吴辉2',
//                'name' => '婚礼主持2'
//            )
//        );
        foreach ($arr as $value) {
            foreach ($value as $key1 => $value1) {
                $arr_list[$key1] = $value1;
            }
            ?>
            <ul class="contacts_ulist">
                <!-- loop -->
                <li class="contacts_item" supplier-id="<?php echo $arr_list['supplier_id']; ?>">
                    <div class="img_bar">
                        <img src="<?php echo $arr_list['avatar']; ?>"/>
                    </div>

                    <div class="contacts_info">
                        <div class="supplier_item_left">
                            <p class="contacts"><?php echo $arr_list['staff_name']; ?></p>

                            <p class="remark"><?php echo $arr_list['type_name']; ?></p>
                        </div>
                        <div class="supplier_item_right">
                            <span  href="#" class="edit">编 辑</span>
                            <span  href="#" class="product">产品列表</span>
                        </div>
                    </div>
                </li>
            </ul>
        <?php } ?>

        <!-- loop end -->
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //右上角增加按钮，进入add_usr
        $(".r_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("supplier/add");?>";
        });

        //点击某个供应商，进入add_supplier进行信息修改
        $(".edit").on("click", function () {
            var supplier_id = escape($(this).parent().parent().parent().attr("supplier-id"));
            location.href = "<?php echo $this->createUrl("supplier/add");?>&supplier_id=" + supplier_id;
        });
        //点击进入产品列表add_product
        $(".product").on("click", function () {
            var supplier_id = escape($(this).parent().parent().parent().attr("supplier-id"));
            location.href = "<?php echo $this->createUrl("product/list");?>&supplier_id=" + supplier_id;
        });

    });
</script>
</body>
</html>
