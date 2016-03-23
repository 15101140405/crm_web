<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>选择折扣</title>
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
        <h2 class="page_title">选择折扣</h2>
    </div>
    <div class="select_ulist_module pad_b50">
        <ul class="select_ulist">
            <?php
            $discount = array( //折扣
                '0' => '9.5',
                '2' => '9',
                '3' => '8.8',
                '4' => '8.5',
                '5' => '8',
                '6' => '7.5'
            );
            foreach ($discount as $key => $value) {
                if ($value == 9.5) {//可以给个变量啊
                    $select = 'round_select_selected';
                } else {
                    $select = '';
                }
                ?>
                <li class="select_ulist_item round_select <?php echo $select; ?>"><?php echo $value; ?></li>
            <?php } ?>
            <!-- <li class="select_ulist_item round_select round_select_selected">9.5折</li>-->
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
            $(".select_ulist li").removeClass("round_select_selected");
            $(this).addClass("round_select_selected");
        });

        //点击确认
        $(".btn").on("click", function () {
            //$.postJSON('#',{"order_id":localStorage.getItem("order_id") , "order_discount":$(".round_select_selected").html()},function(retval){
            //if(retval.success){
            if ($.util.param("from") == "meeting") {
                location.href = "<?php echo $this->createUrl("meeting/bill", array());?>";
            }
            else if ($.util.param("from") == "plan") {
                location.href = "<?php echo $this->createUrl("plan/bill", array());?>";
            }
            else {
                location.href = "<?php echo $this->createUrl("design/bill", array());?>";
            }
            //}else{
            //  alert('太累了，歇一歇，一秒后再试试！');
            //  return false;
            //}
        });

        //点击返回
        $(".l_btn").on("click", function () {
            if ($.util.param("from") == "meeting") {
                location.href = "<?php echo $this->createUrl("meeting/bill", array());?>";
            }
            else if ($.util.param("from") == "plan") {
                location.href = "<?php echo $this->createUrl("plan/bill", array());?>";
            }
            else {
                location.href = "<?php echo $this->createUrl("design/bill", array());?>";
            }
        });
    });
</script>

</body>
</html>
