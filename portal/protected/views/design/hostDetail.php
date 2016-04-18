<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">主持人：<?php echo $service_person['name']?></h2>
    </div>
    <div class="ulist_module pad_b40">
        <ul class="ulist">
        <?php 
            if($_GET['type'] == "edit"){
        ?>
            <li class="ulist_item flex">
                报价
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="price" value="<?php echo $order_product['actual_price']; ?>"/>
                </div>
                <i class="mar_l10 t_green" >元／场</i>
            </li>
        <?php
            }else {
        ?>
            <li class="ulist_item flex">
                报价
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="price" value=""
                           placeholder="请输入报价，如：2000"/>
                </div>
                <i class="mar_l10 t_green">元／场</i>
            </li>
        <?php
            }
        ?>
            <li class="ulist_item flex">
                联系电话
                <i class=" t_green"style="margin-left:3.5rem;"><?php echo $service_person['telephone']?></i>
            </li>
        </ul>
    </div>
<?php if(!empty($supplier_product)){?>
    <div class="select_ulist_module" id="cost">
        <h6 class="m-list-tit" style="border-bottom: 1px solid #eee;line-height:4rem;color:#37CB58;text-align: left;margin-left: 20px;">选择底价 </h6>
        <ul class="select_ulist" id="cost_ul">
    <?php foreach ($supplier_product as $key => $value) { ?>
            <li class="select_ulist_item round_select" supplier-product-id="<?php echo $value['id']?>" cost="<?php  echo $value['unit_cost'];?>"><?php echo $value['name']?><i class="name" style="margin-left:50%;">[<?php  echo $value['unit_cost'];?>元]</i></li>
    <?php }?>
        </ul>
    </div>
<?php }else{?>
    <div class="ulist_module pad_b40">
        <ul class="ulist">
            <li class="ulist_item flex">
                底价
                <div class="flex1">
                    <input class="align_r t_green"  type="text" id="cost_price" value="" placeholder="请输入底价，如：1000"/>
                </div>
                <i class="mar_l10 t_green">元／场</i>
            </li>
        </ul>
    </div>
<?php }?>
    <div class="ulist_module" id="schedule">
        <ul class="ulist charge_list" >
            <li class="ulist_item list_more">
                <div class="item">
                    <p class="name">查看档期</p>
                </div>
            </li>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="insert">提交订单</div>
        <div class="r_btn" id="update">提交订单</div>
        <div class="r_btn" id="del" style="background-color:red;">删除</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>

    //不同页面：初始化、返回、保存（获取、验证数据）、删除

    $(function () {

        //获取用户信息
        var account_id = <?php echo $_SESSION['account_id']?>;

        //页面初始化
        if ($.util.param("type") == "edit") {
            $("#insert").remove();
            <?php if(!empty($order_product)){?>
            $("[supplier-product-id='<?php echo $order_product['product_id']?>']").addClass("round_select_selected");
            <?php }?>
            //此处用php从后端取数
        } else if ($.util.param("type") == "new") {
            $("#del").remove();
            $("#update").remove();
        }
        ;
        if ($.util.param("tab") == "host") {
            $("#number").remove();
            $(".total").remove();
        }else{
            $("#schedule").remove();
        };
        if($.util.param("tab") != "feast"){
            $("#fuwufei").remove();
        };

        //选择底价
        $("#cost li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $("#cost li").removeClass("round_select_selected");
                $(this).addClass("round_select_selected");
            }           
        });

        //查看档期
        $("#schedule").on("click",function(){
            location.href="<?php echo $this->createUrl("service/index");?>&code=&service_team_id=&from=design&supplier_product_id=<?php echo $_GET['supplier_id']?>";
        });


        //新增按钮
        $("#insert").on("click", function () {
            var get_info;
            //主持
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            var product_id = "";
            var cost = "";
        <?php if(!empty($supplier_product)){?>
                product_id = $('.round_select_selected').attr("supplier-product-id");
                cost = $('.round_select_selected').attr("cost");
        <?php }else{?>
                product_id = 0;
                cost = $("#cost_price").val();
        <?php }?>
            get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                'account_id' : account_id ,
                'order_id' : <?php echo $_GET['order_id']?> ,
                'product_id' : product_id,    
                'supplier_id' : <?php echo $_GET['supplier_id']?>,    
                'actual_price' : parseInt($("#price").val()),
                'amount' : 1 ,
                'actual_unit_cost' : cost,
                'update_time' : update_time,
            };
            console.log(get_info);

            //填写内容判断
            if (get_info.actual_price == "" || get_info.amount == "" || get_info.unti == "" || get_info.price == "" ) {
                alert("请补全信息");
                return false;
            }
            else if (isNaN(get_info.actual_price)) {
                alert("请输入正确的价格信息！");
                return false;
            };
            
            $.post("<?php echo $this->createUrl('design/savehost');?>",get_info,function(data){  //bacground data
                location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
            });
        });
<?php if(!empty($order_product)){?>
        //编辑按钮
        $("#update").on("click", function () {
            var get_info;
            //主持
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
        <?php if(!empty($supplier_product)){?>
                product_id = $('.round_select_selected').attr("supplier-product-id");
                cost = $('.round_select_selected').attr("cost");
        <?php }else{?>
                product_id = 0;
                cost = $("#cost_price").val();
        <?php }?>
            get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                'account_id' : account_id ,
                'order_id' : <?php echo $_GET['order_id']?> ,
                'order_product_id' : <?php echo $order_product['id']?>,
                'product_id' : product_id,    
                'supplier_id' : <?php echo $_GET['supplier_id']?>,    
                'actual_price' : parseInt($("#price").val()),
                'amount' : 1 ,
                'actual_unit_cost' : cost,
                'update_time' : update_time,
            };

            //填写内容判断
            if (get_info.price == "") {
                alert("请补全信息");
                return false;
            }
            else if (isNaN(get_info.actual_price)) {
                alert("请输入正确的价格信息！");
                return false;
            }
            
            console.log(get_info);
            $.post("<?php echo $this->createUrl('design/updatehost');?>",get_info,function(data){  //bacground data
                /*alert(data);*/
                location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
            });
        });
        
        //删除按钮
        $("#del").on("click", function () {
            var get_info;
            //主持
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();

            get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                'order_product_id' : <?php echo $order_product['id']?>,
            };
            
            $.post("<?php echo $this->createUrl('design/delhost');?>",get_info,function(data){
                location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";                
            });
        });
<?php }?>
    })
</script>
</body>
</html>
