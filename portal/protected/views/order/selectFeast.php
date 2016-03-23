<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <!-- 所有的子项目报价单订单填写都是此模板 -->

    <!-- 成本项增加也是此模板 -->

    <title>订单填写</title>
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
        <h2 class="page_title">订单填写</h2>
    </div>
    <div class="ulist_module pad_b50">
        <ul class="ulist">
            <?php
            $total = '21342';  //background data
            $arr_feast_order = array(
                'discount_bill' => '4232',
                'table_num' => '32',
                'service_charge_ratio' => '10%',
                'remark' => 'h好'

            );
            ?>
            <li class="ulist_item flex">
                餐标折扣价
                <div class="flex1">
                    <input class="align_r t_green" type="text"
                           placeholder="<?php echo $arr_feast_order['discount_bill']; ?>" id="price"/>
                </div>
                <i class="mar_l10 t_green">元/桌</i>
            </li>
            <li class="ulist_item flex">
                桌数
                <div class="flex1">
                    <input class="align_r t_green" type="text"
                           placeholder="<?php echo $arr_feast_order['table_num']; ?>" id="number"/>
                </div>
                <i class="mar_l10 t_green">桌</i>
            </li>
            <li class="ulist_item flex">
                服务费
                <div class="flex1">
                    <input class="align_r t_green" type="text"
                           placeholder="<?php echo $arr_feast_order['service_charge_ratio']; ?>" id="fee"/>
                </div>
            </li>
            <li class="ulist_item">备注</li>
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="<?php echo $arr_feast_order['remark']; ?>"
                              id="remark"></textarea>
                </div>
            </li>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar">
        <span class="total">总计：&yen;<?php echo $total; ?></span>
        <a class="r_btn" id="add">确定</a>
        <a class="r_btn" id="del">删除</a>
    </div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {

        //若为空订单，则去掉删除按钮
        if ($.util.param("from") == "empty") {
            $("#del").remove();
        }


        //返回
        $(".l_btn").on("click", function () {
            location.href = 'wed&meeting_feast.php?from=' + $.util.param("from") + '&type=' + $.util.param("type");
        })

        //点击确定按钮，并将表单信息提交给后台
        $("#add").on("click", function () {
            //先判断信息是否填全
            if ($("#price").val() == "") {
                alert("请输入折扣价");
                return false;
            } else if ($("#number").val() == "") {
                alert("请输入桌数");
                return false;
            } else if ($("#fee").val() == "") {
                alert("请输入服务费");
                return false;

            } else {//提交信息给后台
                /* var f_info = get_feast_info();
                 $.postJSON('#',f_info,function(retval){
                 if(retval.success){*/
                location.href = "<?php echo $this->createUrl("order/feast", array());?>";
                /*                }else{
                 alert('太累了，歇一歇，一秒后再试试！');
                 return false;
                 }
                 });*/
            }
        })

        /* ===========================
         * 获取界面填写的供应商信息
         * =========================== */
        //后台自动生成feast_id
        function get_feast_info() {
            var f_info = {
                price: $("#price").val(),
                number: $("#number").val(),
                fee: $("#fee").val(),
                remark: $("#remark").val(),
                order_id: localStorage.getItem("order_id")
            }

            return f_info;
        }

        //删除
        $("#del").on("click", function () {

            //$.delectJSON{'#',{feast_id:feast_id},function(retval){
            //  if(retval.success){
            alert('删除成功');
            localStorage.clear();
            location.href = 'wed&meeting_feast.php?from=' + $.util.param("from") + '&type=' + $.util.param("type");
            //  }else{
            //    alert("删除失败");
            //  }
            //  }
        });

    })
</script>
</body>
</html>