<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>新增产品</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <script src="js/zepto.min.js"></script>
    <script src="js/common.js"></script>
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">新增产品</h2>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item" id="name">
                <span class="label">产品名称</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="请输入产品名称"/>
                </div>
            </li>
            <li class="int_ulist_item phone" id="price">
                <span class="label">单价</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="请输入单价"/>
                </div>
                <span class="yuan">元</span>
            </li>
            <li class="int_ulist_item" id="unit">
                <span class="label">单位</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="请输入单位"/>
                </div>
            </li>
            <li class="int_ulist_item phone" id="cost">
                <span class="label">单位成本</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="请输入单位成本"/>
                </div>
                <span class="yuan">元</span>
            </li>
        </ul>
    </div>
    <div class="select_ulist_module">
        <ul class="select_ulist">
            <li class="select_ulist_item round_select round_select_selected" value="2">婚礼产品</li>
            <li class="select_ulist_item round_select" value="1">会议产品</li>
        </ul>
    </div>
    <div class="btn" id="add">确 定</div>
    <div class="btn del hid" id="del">删 除</div>
</article>
<script>
    $(function () {

        var product_id = unescape($.util.param("product_id"));//点击编辑时传来的 供应商id
        var supplier_id = unescape($.util.param("supplier_id"));

        if (product_id != "null") {       //如从编辑入口进入，调取数据，渲染页面
            //background data
            $(".page_title").html("编辑产品");
            $.getJSON("<?php echo $this->createUrl('product/edit');?>",{product_id: product_id},function(retval){
            if(retval.success){
            // var retval = {na: 'xiaos', price: '100', unit: 'yuan', cost: '50'}
            $("#name input").val(retval.na);
            $("#price input").val(retval.price);
            $("#unit input").val(retval.unit);
            $("#cost input").val(retval.cost);
            $("#del").removeClass("hid");
            }else{
                alert('太累了，歇一歇，一秒后再试试！');
                return false;
            }
            })
        }

        //选择产品类型
        $(".select_ulist li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $(".select_ulist li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            }           
        });

        //点击返回按钮，临时储存信息并返回
        $(".l_btn").on("click", function () {

            var p_info = get_product_info();
            var supplierId = escape(supplier_id);
            location.href = "<?php echo $this->createUrl("product/list");?>&supplier_id=" + supplierId;
        });

        //点击确认按钮，提交产品信息
        $("#add").on("click", function () {
            var p_info = get_product_info();

            //判断信息是否完整
            if (p_info.na == "" || p_info.price == "" || p_info.unit == "" || p_info.cost == "") {
                alert("请补全信息");
                return false;
            }

            //background data
            //提交用户信息  $.postJSON 
             $.post('<?php echo $this->createUrl("product/save");?>&product_id='+product_id,p_info,function(retval){
                 /*alert(retval);*/
               // if(retval.success){
                    location.href = "<?php echo $this->createUrl('product/list');?>&supplier_id="+supplier_id;
               // }
               // else{
               //   alert('太累了，歇一歇，一秒后再试试！');
               //   return false;
               // }
             });

        })

        /* ===========================
         * 获取界面填写的产品信息
         * =========================== */
        //后台自动生成一个product_id
        function get_product_info() {
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            var p_info = {
                na: $("#name input").val(),
                category: $(".round_select_selected").attr('value'), 
                price: $("#price input").val(),
                unit: $("#unit input").val(),
                cost: $("#cost input").val(),
                supplier_id: supplier_id,
                product_id:product_id,
                update_time : update_time
            }
            return p_info;
        }

        //删除产品
        $("#del").on("click", function () {
            //background data
            $.post('<?php echo $this->createUrl('product/delete');?>&product_id='+product_id,{product_id:product_id},function(retval){
             // if(retval.success){
                alert('删除成功');
                location.href = "<?php echo $this->createUrl('product/list');?>&supplier_id="+supplier_id;
                 // }else{
                 //   alert("删除失败");
                 // }
            });
        });

    });
</script>
</body>
</html>