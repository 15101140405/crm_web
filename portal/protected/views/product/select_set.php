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
    <script src="js/jquery.1.7.2.min.js"></script>
</head>
<body>
<article>
    <div class="tool_bar">
        <h2 class="page_title">订单填写</h2>
    </div>
    <div class="ulist_module pad_b40" style="margin-top:10px;" id="price">
        <ul class="ulist">
        <?php if($_GET['category'] == 3 || $_GET['category'] == 4){?>
            <li class="ulist_item flex" id="number">
                桌数
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="amount" value=""
                           placeholder="请输入数量"/>
                </div>
            </li>
            <li class="ulist_item flex" id='fuwufei'>
                服务费
                <div class="flex1">
                    <input class="align_r t_green" id="fuwufei_input" type="text" value="" placeholder="<?php  /*echo $productData['service_charge_ratio'];*/?>" id="fee"/>
                </div>
            </li>
        <?php }?>
            <li class="ulist_item">备注</li> 
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="" id="remark"></textarea>
                </div>
            </li>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="insert">提交订单</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>

    //不同页面：初始化、返回、保存（获取、验证数据）、删除

    $(function () {
        $("#insert").on("click",function(){
            var data = {
        <?php if($_GET['category'] == 3 || $_GET['category'] == 4){?>
                'table_num' : $("#amount").val(),
                'fuwufei' : $("#fuwufei_input").val(),
        <?php }?>
                'remark' : $("#remark").val(),
                'set_id' : '<?php echo $_GET['set_id']?>',
                'order_id' : '<?php echo $_GET['order_id']?>',
                'order_type' : '<?php echo $_GET['order_type']?>',
            };
            console.log(data);
            $.post("<?php echo $this->createUrl("product/insert_order_set")?>",data,function(){
                alert("添加套系成功！");
                location.href = "<?php echo $this->createUrl('product/store');?>&code=&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
            });
        })
    })
</script>
</body>
</html>
