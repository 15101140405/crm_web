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
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">新增产品</h2>
    </div>

    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item" >
                <span class="label">产品名称</span>
                <div class="int_bar">
                    <input id="name" class="align_r" type="text" placeholder="请输入产品名称"/>
                </div>
            </li>
            <li class="int_ulist_item phone" >
                <span class="label">单价</span>
                <div class="int_bar">
                    <input id="price" class="align_r" type="text" placeholder="请输入单价"/>
                </div>
                <span class="yuan">元</span>
            </li>
            <li class="int_ulist_item" >
                <span class="label">单位</span>
                <div class="int_bar">
                    <input id="unit" class="align_r" type="text" placeholder="请输入单位"/>
                </div>
            </li>
            <li class="ulist_item">备注</li> 
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="请输入备注" id="description"></textarea>
                </div>
            </li> 
        </ul>
    </div>
    <div class="btn" id="add">确 定</div>
    <div class="btn" id="edit">确 定</div>
    <div class="btn del" id="del">删 除</div>

</article>
<script>
    $(function () {

        //初始渲染
        if ("<?php echo $type ?>" == "edit") {       //如从编辑入口进入，调取数据，渲染页面
            $(".page_title").html("编辑产品");
            $("#add").remove();
            $("#name").val("<?php echo $product['product_name']?>");
            $("#price").val("<?php echo $product['price']?>");
            $("#unit").val("<?php echo $product['unit']?>");
            $("#description").val("<?php echo $product['description']?>");
        }else{
            $("#edit").remove();
            $("#del").remove();
        }


        //新增
        $("#add").on("click", function () {
            var p_info = get_product_info();

            //判断信息是否完整
            if (p_info.na == "" || p_info.price == "" || p_info.unit == "" ) {
                alert("请补全信息");
                return false;
            }else if(p_info.supplier_id == ""){
                alert("请选择供应商");
                return false;
            }
            console.log(p_info);
            //提交用户信息 
             $.post('<?php echo $this->createUrl("service/insert_product");?>',p_info,function(){
                
                location.href = "<?php echo $this->createUrl('service/product');?>";
             });
        });

        //编辑
        $("#edit").on("click", function () {
            var p_info = get_product_info();

            //判断信息是否完整
            if (p_info.na == "" || p_info.price == "" || p_info.unit == "" ) {
                alert("请补全信息");
                return false;
            }else if(p_info.supplier_id == ""){
                alert("请选择供应商");
                return false;
            }
            console.log(p_info);
            //提交用户信息 

            $.post('<?php echo $this->createUrl("service/update_product");?>',p_info,function(retval){
                /*alert(retval);*/
                location.href = "<?php echo $this->createUrl('service/product');?>";
            });
        });

        //删除
        $("#del").on("click", function () {
            //background data
            $.post('<?php echo $this->createUrl('service/del_product');?>',{product_id:'<?php echo $product['id']?>'},function(retval){
                /*alert(retval);*/
                location.href = "<?php echo $this->createUrl('service/product');?>";
            });
        }); 


        /* ===========================
         * 获取界面填写的产品信息
         * =========================== */
        //后台自动生成一个product_id
        function get_product_info() {
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            var p_info = {
                    product_id: "<?php echo $product['id']?>",
                    na: $("#name").val(),
                    price: $("#price").val(),
                    unit: $("#unit").val(),
                    update_time : update_time,
                    description : $("#description").val()
                }
            return p_info;
        }
    });
</script>
</body>
</html>