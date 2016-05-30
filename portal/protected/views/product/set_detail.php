<!DOCTYPE html>
<html>

<head>
    <title>产品介绍</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base1.css">
    <link rel="stylesheet" type="text/css" href="css/weeding_set.css">
    <!-- <link rel="stylesheet" href="css/islider.css" /> -->
    <script src="js/islider.js"></script>
    <script src="js/plugins/islider_desktop.js"></script>
</head>
<body style="background:#fff;">
    <h2 class="set_title">产品介绍</h2>
    <ul class="set_list">
        <?php foreach ($img as $key => $value) { ?>
        <li class="set_item">
            <img src="<?php if (!empty($value)) {echo $value['img_url'];}?>" alt="">
        </li>
        <?php }?>
    </ul>
    <div class="fixed_bot_btn flexbox v_center">
        <div class="flex1 fixed_content" style="width: 40%;float: left;">
            <p class="now_price">&yen;<strong><?php
            /*if ($_GET['from'] == "set") {*/
                echo $supplier_product['final_price']; 
            /*} else {
                echo $supplier_product['unit_price'];
            }
            */?></strong>
            </p>
        </div>
        <button id="insert" style="float: right;">加入订单</button>
    </div>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function(){
        $("#insert").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/selectorder');?>&product_id=<?php echo $_GET['product_id']?>&tab=set&category=<?php echo $_GET['category']?>&from=<?php echo $_GET['from']?>";
        });
    })
</script>
</body>
</html>