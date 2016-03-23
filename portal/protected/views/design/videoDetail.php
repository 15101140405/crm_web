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
    <div class="ulist_module pad_b40">
        <ul class="ulist">
            <li class="ulist_item flex">
                折扣价
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="price" value="" placeholder="4000"/>
                </div>
                <i class="mar_l10 t_green">元</i>
            </li>
        </ul>
    </div>
    <!-- @$ 隐藏 选择套餐 -->
    <div class="ulist_module pad_b40 hid" id="set">
        <div class="module_title">选择套餐</div>
        <ul class="select_ulist">
            <li class="select_ulist_item select select_selected" price="2000">双机位</li>   <!-- price  用php从后端取数据 -->
            <li class="select_ulist_item select" price="3000">三机位</li>                   <!-- price  用php从后端取数据 -->
            <li class="select_ulist_item select" price="4000">三机位＋摇臂</li>              <!-- price  用php从后端取数据 -->
        </ul>
    </div>
    <!-- @$ 隐藏 选择供应商 -->
    <div class="ulist_module pad_b40 hid" id="supplier">
        <div class="module_title">选择供应商</div>
        <ul class="select_ulist">
            <li class="select_ulist_item select select_selected">小李</li>
            <li class="select_ulist_item select">小张</li>
            <li class="select_ulist_item select">小王</li>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar">
        <span class="total">总计：&yen;100000</span>
        <a class="r_btn">提交订单</a>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //页面初始化
        if ($.util.param("type") == "edit") {
            $("#price").attr("value", "1000");  //此处用php从后端取：已存储的折扣价
        } else {
            $("#price").attr("value", "2000");  //此处用php从后端取：套餐1的标价
        }

        //选择套餐，套餐价位
        $("#set .select_ulist li").on("click", function () {
            $("#set li.select_ulist_item").removeClass("select_selected");
            $(this).addClass("select_selected");
            $("#price").attr("value", $(this).attr("price"))
        })

        //选择供应商
        $("#supplier .select_ulist li").on("click", function () {
            $("#supplier li.select_ulist_item").removeClass("select_selected");
            $(this).addClass("select_selected");
        })

        //返回按钮
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&item=" + $.util.param("tab");
        });

        //保存按钮
        $(".r_btn").on("click", function () {
            var get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                //order_id : lacalStorage.getItem("order_id") ,
                product_id: $.util.param("from"),
                price: $("#price").val(),
                amount: $("#amount").val()
            };

            console.log(get_info);
            //填写内容判断
            if (get_info.price == "" || get_info.amount == "") {
                alert("请补全信息");
                return false;
            }
            else if (isNaN(get_info.price)) {
                alert("请输入正确的价格信息！");
                return false;
            } else if (isNaN(get_info.amount)) {
                alert("请输入正确的数量信息！");
                return false;
            }

            /*postJSON("#.php",get_info,function(data){
             if(data.success){*/
            alert("提交成功！");
            //location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&item=" + $.util.param("tab") ;
            /*}else {
             alert("提交失败，再试一次！");
             }
             })*/
        });


    })
</script>
</body>
</html>
