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
    <script src="js/zepto.min.js"></script>
    <script src="js/utility/util.js" type="text/javascript"></script>
    <script src="js/common.js"></script>
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">新增联系人</h2>
    </div>
    <div class="select_ulist_module pad_b50">
        <ul class="select_ulist">
            <?php
            //background data//
            $i=0;
            foreach ($types as  $type) {
                if ($i == 0) {
                    $selected = 'round_select_selected';
                } else {
                    $selected = '';
                }
                $i++;
                ?>
                <li type_id="<?php echo $type->id;?>" class="select_ulist_item round_select <?php echo $selected; ?>"><?php echo $type->name; ?></li>
            <?php } ?>
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

        var supplier_id = unescape($.util.param("supplier_id"));
        //选择部门
        $(".select_ulist li").on("click", function () {
            $(".select_ulist li").removeClass("round_select_selected");
            $(this).addClass("round_select_selected");
        });

        //点击确认
        $(".btn").on("click", function () {
            var choosetype = escape($(".round_select_selected").html());
            var choosetypeid = $(".round_select_selected").attr("type_id");
            location.href = "<?php echo $this->createUrl("supplier/add");?>&choose_type=" + choosetype + "&choose_type_id=" + choosetypeid + "&supplier_id=" + supplier_id;
        });

        //点击返回
        $(".l_btn").on("click", function () {
            var s_info = $.parseJSON(localStorage.getItem("s_info"));
            var choosetype = escape(s_info.supplier_type);
            var choosetypeid = escape(s_info.supplier_type_id);;
            location.href = "<?php echo $this->createUrl("supplier/add");?>&choose_type=" + choosetype +"&choose_type_id=" + choosetypeid + "&supplier_id=" + supplier_id;
        });
    });
</script>
</body>
</html>