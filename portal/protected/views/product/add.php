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
<?php   if($_GET['product_id'] == ""){
                if($_GET['supplier_id'] == ""){
?>
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
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item list_more" id="supplier">
                <span class="label">供应商</span>
                <span class="supplier_type" id="supplier_name"></span>
            </li>
        </ul>
    </div>
    <div class="btn" id="add">确 定</div>
    <div class="btn" id="edit">确 定</div>
    <div class="btn del hid" id="del">删 除</div>
<?php           }else{?>
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
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item list_more" id="supplier">
                <span class="label">供应商</span>
                <span class="supplier_type" id="supplier_name">[<?php echo $supplier_name?>]</span>
            </li>
        </ul>
    </div>
    <div class="btn" id="add">确 定</div>
    <div class="btn" id="edit">确 定</div>
    <div class="btn del hid" id="del">删 除</div>

<?php           }
        }else{ ?>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item" id="name">
                <span class="label">产品名称</span>
                <div class="int_bar">
                    <input class="align_r" type="text" value="<?php echo $supplier_product['name']?>" placeholder="请输入产品名称"/>
                </div>
            </li>
            <li class="int_ulist_item phone" id="price">
                <span class="label">单价</span>
                <div class="int_bar">
                    <input class="align_r" type="text" value="<?php echo $supplier_product['unit_price']?>" placeholder="请输入单价"/>
                </div>
                <span class="yuan">元</span>
            </li>
            <li class="int_ulist_item" id="unit">
                <span class="label">单位</span>
                <div class="int_bar">
                    <input class="align_r" type="text" value="<?php echo $supplier_product['unit']?>" placeholder="请输入单位"/>
                </div>
            </li>
            <li class="int_ulist_item phone" id="cost">
                <span class="label">单位成本</span>
                <div class="int_bar">
                    <input class="align_r" type="text" value="<?php echo $supplier_product['unit_cost']?>" placeholder="请输入单位成本"/>
                </div>
                <span class="yuan">元</span>
            </li>
        </ul>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item list_more" id="supplier">
                <span class="label">供应商</span>
                <span class="supplier_type" id="supplier_name">[<?php echo $supplier_name?>]</span>
            </li>
        </ul>
    </div>
    <div class="btn" id="add">确 定</div>
    <div class="btn" id="edit">确 定</div>
    <div class="btn del" id="del">删 除</div>
<?php   } ?>
</article>
<script>
    $(function () {
        //初始渲染
        if ("<?php echo $_GET['product_id']?>" != "") {       //如从编辑入口进入，调取数据，渲染页面
            $(".page_title").html("编辑产品");
            $("#add").remove();
        }else{
            $("#edit").remove();
            $("#del").remove();
        }

        //选择产品类型
        $(".select_ulist li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $(".select_ulist li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            };
        });

        //新增
        $("#add").on("click", function () {
            var p_info = get_product_info();

            //判断信息是否完整
            if (p_info.na == "" || p_info.price == "" || p_info.unit == "" || p_info.cost == "") {
                alert("请补全信息");
                return false;
            }else if(p_info.supplier_id == ""){
                alert("请选择供应商");
                return false;
            }
            console.log(p_info);
            //提交用户信息 
             $.post('<?php echo $this->createUrl("product/insert");?>',p_info,function(){
                location.href = "<?php echo $this->createUrl('product/list');?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>";
             });
        });

        //编辑
        $("#edit").on("click", function () {
            var p_info = get_product_info();

            //判断信息是否完整
            if (p_info.na == "" || p_info.price == "" || p_info.unit == "" || p_info.cost == "") {
                alert("请补全信息");
                return false;
            }else if(p_info.supplier_id == ""){
                alert("请选择供应商");
                return false;
            }
            console.log(p_info);
            //提交用户信息 
             $.post('<?php echo $this->createUrl("product/edit");?>',p_info,function(){
                location.href = "<?php echo $this->createUrl('product/list');?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>";
             });
        });

        //删除
        $("#del").on("click", function () {
            //background data
            $.post('<?php echo $this->createUrl('product/del');?>',{product_id:'<?php echo $_GET['product_id']?>'},function(){
                alert('删除成功');
                location.href = "<?php echo $this->createUrl('product/list');?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>";
            });
        }); 

        //跳转选择供应商
        $("#supplier").on("click",function(){
            location.href = "<?php echo $this->createUrl('supplier/list');?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=<?php echo $_GET['supplier_id']?>&product_id=<?php echo $_GET['product_id']?>";
        });


        /* ===========================
         * 获取界面填写的产品信息
         * =========================== */
        //后台自动生成一个product_id
        function get_product_info() {
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            var p_info = {
                    product_id: "<?php echo $_GET['product_id']?>",
                    na: $("#name input").val(),
                    category: "<?php echo $_GET['category']?>", 
                    price: $("#price input").val(),
                    unit: $("#unit input").val(),
                    cost: $("#cost input").val(),
                    supplier_id: "<?php echo $_GET['supplier_id']?>",
                    supplier_type_id: "<?php echo $_GET['supplier_type']?>",
                    update_time : update_time
                }
            return p_info;
        }
    });
</script>
</body>
</html>