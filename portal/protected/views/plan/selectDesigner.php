<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>选择策划师</title>
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
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">选择策划师</h2>
        <div class="r_btn">重选</div>
    </div>
    <div class="contacts_ulist_module">
        <!-- loop -->
        <h4 class="contacts_index">A</h4>
        <ul class="contacts_ulist">
            <!-- loop -->
            <li class="contacts_item selected" designer-id="000">
                <div class="img_bar">
                    <img src="images/usr_icon.jpg"/>
                </div>
                <div class="contacts_info_left">
                    <p class="contacts">阿斯</p>
                    <p class="remark">店长</p>
                </div>
            </li>
            <!-- loop -->
        </ul>
        <!-- loop end -->
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<script>
    $(function () {
        //判断是否已选策划师
        var order_designer = "";  //php 为此变量赋值
        if (order_designer == "") { // 正常渲染页面,去掉selected类、r_btn标签
            //选择策划师
            $(".selected").addClass("list_more");
            $(".selected").removeClass("selected");
            $(".r_btn").remove();
            $("li.contacts_item").on("click", function () {

                //弹窗：确定／取消
                var name = $(this).find("p.contacts").html();
                var r = confirm("确定把订单分配给" + name + "？")
                if (r == true) {
                    $.postJSON("#", {
                        "order_id": localStorage.getItem("order_id"),
                        "designer_id": $(this).attr("designer-id")
                    }, function (retval) {
                        if (retval.success) {
                            location.href = "<?php echo $this->createUrl("plan/selectDesigner", array());?>
                        }
                        else {
                            alert('分配失败，一秒后再试试！');
                            return false;
                        }
                    })
                }
                else {
                    return false;
                }
            });
        }

        //点击重选
        $(".r_btn").on("click", function () {
            $.postJSON("#", {
                "order_id": localStorage.getItem("order_id"),
                "designer_id": $(this).attr("designer-id")
            }, function (retval) {
                if (retval.success) {
                    location.href = "<?php echo $this->createUrl("plan/selectDesigner", array());?>&" + $.util.param("from");
                }
                else {
                    alert('重选失败，一秒后再试试！');
                    return false;
                }
            })
        })

        //返回
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("plan/detail", array());?>&from=" + $.util.param("from");
        })
    })
</script>
</body>
</html>
