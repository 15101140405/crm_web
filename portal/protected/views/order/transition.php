<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>转移客户</title>
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
        <h2 class="page_title">转移订单</h2>
    </div>
    <div class="contacts_ulist_module">
        <?php
        /*$arr_planner = array(
            '0' => array(
                'planner_id' => '11',
                'img' => 'usr_icon.jpg',
                'contacter' => '阿斯',
                'remark' => '店长'
            ),
            '1' => array(
                'planner_id' => '112',
                'img' => 'usr_icon.jpg',
                'contacter' => '阿斯2',
                'remark' => '老板'
            )

        );*/
        foreach ($arr_staff as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $staff[$key1] = $value1;
            }
            ?>
            <!-- loop -->
            <h4 class="contacts_index">A</h4>
            <ul class="contacts_ulist">
                <!-- loop -->
                <li class="contacts_item" staff-id="<?php echo $staff['id']; ?>">
                    <div class="img_bar">
                        <img src="images/dudaohui.png"/>
                    </div>
                    <div class="contacts_info">
                        <p class="contacts"><?php echo $staff['name']; ?></p>
                        <!-- <p class="remark"></p> -->
                    </div>
                </li>
                <!-- loop -->
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

        //渲染页面

        //选择策划师
        $("li.contacts_item").on("click", function () {
            //弹窗：确定／取消
            var confirm_name = $(this).find(".contacts").html();
            var r = confirm("确定把订单分配给" + confirm_name + "?") //这里选择有点问题
            console.log({"order_id":"<?php echo $_GET['order_id']?>" , "staff_id":$(this).attr("staff-id"), "type":"<?php echo $_GET['type']?>"});
            if (r == true) {
                var data = {
                    "order_id":"<?php echo $_GET['order_id']?>" , 
                    "staff_id":$(this).attr("staff-id"), 
                    "type":"<?php echo $_GET['type']?>"
                };
                console.log(data);
                $.post("<?php echo $this->createUrl("order/ordertransition")?>",data,function(retval){
                    alert(retval);
                    if("<?php echo $_GET['from']?>" == 'meeting'){
                        location.href = "<?php echo $this->createUrl("meeting/bill")?>&order_id=<?php echo $_GET['order_id']?>";
                    }else if("<?php echo $_GET['from']?>" == 'wedding'){
                        location.href = "<?php echo $this->createUrl("design/bill")?>&order_id=<?php echo $_GET['order_id']?>";
                    };
                });
            }else {
                return false;
            }
        })

        //返回
        $(".l_btn").on("click", function () {
            if ($.util.param("from") == "meeting") {
                location.href = "<?php echo $this->createUrl("meeting/detail", array());?>";
            }
            else if ($.util.param("from") == "plan") {
                location.href = "<?php echo $this->createUrl("plan/detail", array());?>";
            }
            else {
                location.href = "<?php echo $this->createUrl("design/detail");?>";
            }
        })
    })
</script>

</body>
</html>
