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
        <div class="r_btn">保存</div>
    </div>
    <div class="pic_select_module pad_b50">
        <ul class="pic_ulist">
            <?php
            $arr_select_layout = array( //所需数据
                '0' => array(
                    'img' => 'meeting_layout.jpg',
                    'type' => '圆形1',
                    'select' => 'round_select_selected'
                ),
                '1' => array(
                    'img' => 'meeting_layout.jpg',
                    'type' => 'T形2',
                    'select' => ''
                ),
                '2' => array(
                    'img' => 'meeting_layout.jpg',
                    'type' => '方形3',
                    'select' => ''
                ),
                '3' => array(
                    'img' => 'meeting_layout.jpg',
                    'type' => '横排4',
                    'select' => ''
                )
            );
            foreach ($arr_select_layout as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_layout[$key1] = $value1;
                }
                echo '<li class="pic_ulist_item round_select ' . $arr_layout['select'] . '">' .
                    '<div class="img_bar">' .
                    '<img src="images/' . $arr_layout['img'] . '"/>' .
                    '</div>' .
                    '<i>' . $arr_layout['type'] . '</i>' .
                    '</li>';
            }
            ?>
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<!-- 搜索的交互方案待定 -->
<script src="js/search.js"></script>
<script>
    $(function () {

        //保存按钮
        $(".r_btn").on("click", function () {
            /*$.postJASON("#",{"order_id":localStorage.getItem("order_id"),"layout_name":$("li.round_select_selected i").html()},function(retval){
             if(retval.success){*/
            location.href = "<?php echo $this->createUrl("meeting/detailInfo", array());?>";
            /*}
             else
             {
             alert("保存失败，再试一次！")
             }
             })*/
        });

        //返回按钮
        $(".l_btn").on("click", function () {
            //清空已选台型
            localStorage.removeItem("choose_layout");
            location.href = "<?php echo $this->createUrl("meeting/detailInfo", array());?>";
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
