<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>新增－推单渠道</title>
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
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">婚礼</h2>
        <div class="r_btn">确定</div>
    </div>
    <div class="int_ulist_module">
        <h4 class="module_title">新增推单渠道</h4>
        <ul class="int_ulist">
            <li class="int_ulist_item">
                <span class="label">名称</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="请输入名称" id="link_name"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">联系电话</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="联系电话" id="link_phone"/>
                </div>
            </li>
            <!-- <li class="int_ulist_item">
                <span class="label">微信</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="微信" id="link_wechat"/>
                </div>
            </li>
            <li class="int_ulist_item">
                <span class="label">QQ</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="QQ" id="link_qq"/>
                </div>
            </li> -->
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //点击返回按钮
        /*$(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("plan/customerName", array());?>&from=" + $.util.param("from") + "order_id=" + $.util.param("order_id");
        })*/

        //点确定按钮
        $(".r_btn").on("click", function () {
            var get_info = getinfo();

            if ($("#link_name").val() == "") {
                alert("请输入名称");
            } else if ($("#link_phone").val() == "" || !$.regex.isPhone($("#link_phone").val())) {
                alert("请输入正确的联系电话");
            };
            console.log(get_info);
            $.post('<?php echo $this->createUrl("plan/channelinsert", array());?>',get_info,function(){
                location.href = "<?php echo $this->createUrl("plan/chooseChannel", array("from" => "design", "order_id" => $_GET['order_id']));?>";
            });
        });


        //获得页面数据
        function getinfo() {
            var get_info = {
                name: $("#link_name").val(),
                phone: $("#link_phone").val(),
                /*link_wechat: $("#link_wechat").val(),
                link_qq: $("#link_qq").val()*/
                order_id:$.util.param("order_id"),
            }
            return get_info;
        };
    })
</script>
</body>
</html>
