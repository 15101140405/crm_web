<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>会议－台型选择</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="css/calendar.css" rel="stylesheet" type="text/css"/>
</head>
<body class="bg_white">
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">选择台型</h2>
        <div class="r_btn" data-icon="&#xe6a3;"></div>
    </div>
    <div class="pic_select_module pad_b50">
        <ul class="pic_ulist">

            <?php
            // $arr_select_layout = array( //+++++++++++++++++++++++++++所需数据，台型
            //     '0' => array(
            //         'layout_id' => '1',
            //         'image' => 'meeting_layout.jpg',
            //         'title' => '圆形1'
            //     ),
            //     '1' => array(
            //         'layout_id' => '2',
            //         'image' => 'meeting_layout.jpg',
            //         'title' => 'T形2'
            //     ),
            //     '2' => array(
            //         'layout_id' => '3',
            //         'image' => 'meeting_layout.jpg',
            //         'title' => '方形3'
            //     ),
            //     '3' => array(
            //         'layout_id' => '4',
            //         'image' => 'meeting_layout.jpg',
            //         'title' => '横排4'
            //     )
            // );
            foreach ($arr_select_layout as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_layout[$key1] = $value1;
                    // if ($arr_layout['layout_id'] == '1') {//初始选择状态
                    //     $select = 'round_select_selected';
                    // } else {
                        $select = '';
                    // }
                }
                echo '<li class="pic_ulist_item round_select ' . $select . '" layout_id="' . $arr_layout['layout_id'] . '">' .
                    '<div class="img_bar">' .
                    '<img src="images/' . $arr_layout['image'] . '"/>' .
                    '</div>' .
                    '<i>' . $arr_layout['title'] . '</i>' .
                    '</li>';
            }
            ?>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
        <a class="r_btn">确定</a>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<!-- 搜索的交互方案待定 -->
<script src="js/search.js"></script>
<script>
    $(function () {

        //确定按钮
        $(".r_btn").on("click", function () {
            var choose_obj = $(".pic_ulist .round_select_selected");
            var choose_one = choose_obj.attr("layout_id");
            localStorage.setItem("choose_layout", JSON.stringify(choose_one));
            alert("C_ID:"+choose_obj.attr("data-id")+",C_NAME:"+choose_obj.val()+","+localStorage.getItem("choose_linkman"));
            // alert(choose_obj.attr("data-id"));
            $.postJSON('<?php echo $this->createUrl("meeting/insertLayout");?>', meeting_info, function (retval) {
                if (retval.success = 1) {
                    location.href = "<?php echo $this->createUrl("meeting/detail", array("order_id" => "revtal.data"));?>";
                    localStorage.clear();
                } else {
                    alert("太忙了，等一等");
                }
            })
            location.href = "<?php echo $this->createUrl("meeting/detail");?>";
        });

        //返回按钮
        $(".l_btn").on("click", function () {
            //清空已选台型
            localStorage.removeItem("choose_layout");
            location.href = "<?php echo $this->createUrl("meeting/selectLinkman");?>";
        });

        //台型选择勾选
        $(".pic_ulist li").on("click", function () {
            $(".pic_ulist li").removeClass("round_select_selected");
            $(this).addClass("round_select_selected");
        });

    })
</script>
</body>
</html>
