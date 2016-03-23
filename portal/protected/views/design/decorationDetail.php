<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <!-- 所有的子项目报价单订单填写都是此模板 -->

    <!-- 成本项增加也是此模板 -->

    <title>添加商品</title>
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
        <h2 class="page_title">添加商品</h2>
    </div>
    <div class="ulist_module pad_b50">
        <ul class="ulist">
        <?php 
            if($_GET['type'] == 'edit'){
        ?>
            <!-- 文件上传(上传前) -->
            <!-- <li class="ulist_item flex list_more" id="picture_non">
                参考图
                <div class="flex1">
                    <input class="align_r" id="get_picture" type="file" capture="camera"/>
                </div>
            </li> -->
            <!-- 文件上传(上传后) -->
            <!-- <li class="ulist_item flex hid" id="picture_has">
                参考图
                <div class="flex1">
                    <div class="thumbnail float_r">
                        <img src="images/meeting_layout.jpg"/>
                    </div>
                </div>
            </li> -->
            <li class="ulist_item flex">
                名称
                <div class="flex1">
                    <input class="align_r t_green" id="na" type="text" placeholder="请输入单价" value="<?php echo $product_data['name'];?>"/>
                </div>
            </li>
            <li class="ulist_item flex">
                单价
                <div class="flex1">
                    <input class="align_r t_green" id="price" type="text" placeholder="请输入单价" value="<?php echo $product_data['unit_price'];?>"/>
                </div>
            </li>
            <li class="ulist_item flex">
                单位
                <div class="flex1">
                    <input class="align_r t_green" id="unit" type="text" placeholder="请输入单位" value="<?php echo $product_data['unit'];?>"/>
                </div>
            </li>
            <li class="ulist_item flex">
                数量
                <div class="flex1">
                    <input class="align_r t_green" id="amount" type="text" placeholder="输入数量" value="<?php echo $amount;?>"/>
                </div>
            </li>
            <li class="ulist_item flex">
                单位成本
                <div class="flex1">
                    <input class="align_r t_green" id="cost" type="text" placeholder="输入单位成本" value="<?php echo $product_data['unit_cost'];?>"/>
                </div>
            </li>
            <li class="remark">
                <p class="mar_tb10">备注</p>
                <div class="text_bar">
                    <textarea maxlength="70" id="remark" placeholder="写下具体要求"><?php echo $product_data['description'];?>"</textarea>
                </div>
            </li>
        <?php
            }else if($_GET['type'] == 'new'){
        ?>
            <!-- 文件上传(上传前) -->
            <!-- <li class="ulist_item flex list_more" id="picture_non">
                参考图
                <div class="flex1">
                    <input class="align_r" id="get_picture" type="file" capture="camera"/>
                </div>
            </li> -->
            <!-- 文件上传(上传后) -->
            <!-- <li class="ulist_item flex hid" id="picture_has">
                参考图
                <div class="flex1">
                    <div class="thumbnail float_r">
                        <img src="images/meeting_layout.jpg"/>
                    </div>
                </div>
            </li> -->
            <li class="ulist_item flex">
                名称
                <div class="flex1">
                    <input class="align_r t_green" id="na" type="text" placeholder="请输入单价"/>
                </div>
            </li>
            <li class="ulist_item flex">
                单价
                <div class="flex1">
                    <input class="align_r t_green" id="price" type="text" placeholder="请输入单价"/>
                </div>
            </li>
            <li class="ulist_item flex">
                单位
                <div class="flex1">
                    <input class="align_r t_green" id="unit" type="text" placeholder="请输入单位"/>
                </div>
            </li>
            <li class="ulist_item flex">
                数量
                <div class="flex1">
                    <input class="align_r t_green" id="amount" type="text" placeholder="输入数量"/>
                </div>
            </li>
            <li class="ulist_item flex">
                单位成本
                <div class="flex1">
                    <input class="align_r t_green" id="cost" type="text" placeholder="输入单位成本"/>
                </div>
            </li>
            <li class="remark">
                <p class="mar_tb10">备注</p>
                <div class="text_bar">
                    <textarea maxlength="70" id="remark" placeholder="写下具体要求"></textarea>
                </div>
            </li>
        <?php
        }
        ?>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar">
        <div class="r_btn" id="insert">提交订单</div>
        <div class="r_btn" id="update">提交订单</div>
        <div class="r_btn" id="del" style="background-color:red;">删除</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //判断是“新增”，还是“修改”
        if ($.util.param("product_id") == null) {
            $("#del").remove();
            $("#update").remove();
        } else {
            $("#picture_non").remove();
            $("#picture_has").removeClass("hid");
            $("#insert").remove();


            //php渲染页面
        }

        //上传图片后，预览
        $("#get_picture").on("click", function () {
            if ($("#uploadFile").val() != null) {
                $("#picture_non").remove();
                $("#picture_has").removeClass("hid");
                $("img").attr("src", $("#uploadFile").val());
            }
            ;
        })

        //返回
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl('design/decoration', array())?>&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
        })

        //新增单品
        $("#insert").on("click", function () {

            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();

            var get_info = {
                account_id : 1,
                order_id : parseInt($.util.param("order_id")) ,
                product_id: parseInt($.util.param("product_id")),
                name : $("#na").val(),
                actual_price: parseFloat($("#price").val()),
                unit: $("#unit").val(),
                amount: parseInt($("#amount").val()),
                actual_unit_cost:parseFloat($("#cost").val()),
                update_time: update_time,
                actual_service_ratio: 0,
                remark: $("#remark").val(),
                /*picture: $("#uploadFile").val(),*/
            }
            console.log(get_info);
            //填写内容判断
            if (get_info.actual_price == "" || get_info.amount == "" || get_info.unti == "" || get_info.name == "" || get_info.actual_unit_cost == "" || get_info.remark == "") {
                alert("请补全信息");
                return false;
            }
            else if (isNaN(get_info.actual_price)) {
                alert("请输入正确的价格信息！");
                return false;
            } else if (isNaN(get_info.amount)) {
                alert("请输入正确的数量信息！");
                return false;
            }

            $.post("<?php echo $this->createUrl('design/savedec', array());?>",get_info,function(data){
                alert("提交成功！");
                location.href = "<?php echo $this->createUrl('design/decoration', array());?>&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
            })
        });

        //编辑单品
        $("#update").on("click", function () {
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();


            var get_info = {
                account_id : 1,
                order_id : parseInt(localStorage.getItem("order_id")) ,
                product_id: parseInt($.util.param("product_id")),
                name : $("#na").val(),
                actual_price: parseFloat($("#price").val()),
                unit: $("#unit").val(),
                amount: parseInt($("#amount").val()),
                actual_unit_cost:parseFloat($("#cost").val()),
                update_time: update_time,
                actual_service_ratio: 0,
                remark: $("#remark").val(),
                /*picture: $("#uploadFile").val(),*/
            }

            //填写内容判断
            if (get_info.actual_price == "" || get_info.amount == "" || get_info.unti == "") {
                alert("请补全信息");
                return false;
            }
            else if (isNaN(get_info.actual_price)) {
                alert("请输入正确的价格信息！");
                return false;
            } else if (isNaN(get_info.amount)) {
                alert("请输入正确的数量信息！");
                return false;
            }

            $.post("<?php echo $this->createUrl('design/updatedec', array());?>",get_info,function(data){
                alert("提交成功！");
                location.href = "<?php echo $this->createUrl('design/decoration', array());?>&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
            })
        });

        //删除按钮
        $("#del").on("click", function () {
            var get_info = {
                order_id : parseInt($.util.param("order_id")),
                product_id : parseInt($.util.param("product_id"))
            }
            console.log(get_info);
            $.post("<?php echo $this->createUrl('design/deltp');?>",get_info,function(retval){
                alert('删除成功');
                location.href = "<?php echo $this->createUrl('design/decoration', array());?>&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
            });
        });

    })
</script>
</body>
</html>
