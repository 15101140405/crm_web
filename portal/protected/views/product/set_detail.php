<!DOCTYPE html>
<html>

<head>
    <title>套餐详情</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/weeding_set.css">
</head>
<body style="background:#fff;">
    <h2 class="set_title">产品介绍</h2>
    <ul class="set_list">
        <li class="set_item">
            <img src="images/3299.png" alt="">
        </li>
        <li class="set_item">
            <img src="images/weeding_set_img2.jpg" alt="">
        </li>
    </ul>
    <div class="fixed_bot_btn flexbox v_center">
        <div class="flex1 fixed_content" style="width: 40%;float: left;">
            <p class="now_price">&yen;<strong>88</strong>
            </p>
            <del class="original_price">&yen;<em>160</em></del>
        </div>
        <button id="insert" style="float: right;">加入订单</button>
    </div>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function(){
        $("#insert").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/selectorder');?>&product_id=<?php echo $_GET['product_id']?>&category=<?php echo $_GET['category']?>";
        });
    })
</script>
</body>
</html>