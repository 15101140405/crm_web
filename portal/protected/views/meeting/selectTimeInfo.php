<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>会议－基本信息</title>
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
        <h2 class="page_title">会议</h2>
        <div class="r_btn">保存</div>
    </div>
    <div class="select_ulist_module">
        <h4 class="module_title">会议时间</h4>
        <ul class="select_ulist">
            <li class="select_ulist_item select select_selected">上午</li>
            <li class="select_ulist_item select">下午</li>
            <li class="select_ulist_item select">全天</li>
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>

<script>
    $(function () {

        //保存按钮
        $(".r_btn").on("click", function () {  //background tata
            /*$.postJASON("#",{"order_id":localStorage.getItem("order_id"),"time":$("li.select_selected").html()},function(retval){
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
            //清空已选时间
            location.href = "<?php echo $this->createUrl("meeting/detailInfo", array());?>";
        });

        //台型选择勾选
        $(".select_ulist li").on("click", function () {
            $(".select_ulist li").removeClass("select_selected");
            $(this).addClass("select_selected");
        });

    })
</script>
</body>
</html>
