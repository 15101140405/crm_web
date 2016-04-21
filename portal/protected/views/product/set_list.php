<!DOCTYPE html>
<html>

<head>
    <title>套餐</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base1.css">
    <link rel="stylesheet" type="text/css" href="css/weeding_set.css">
</head>

<body>
    <ul class="set_list">
        <li class="set_item" product-id="0">
            <a>
                <img src="images/weeding_sets_img.jpg" alt="">
                <div class="info flexbox v_center">
                    <p class="flex1">凤凰花开</p>
                    <p class="price"><strong>3288</strong>元</p>
                </div>
            </a>
        </li>
        <li class="set_item" product-id="0">
            <a >
                <img src="images/weeding_sets_img.jpg" alt="">
                <div class="info flexbox v_center">
                    <p class="flex1">七彩流云</p>
                    <p class="price"><strong>5888</strong>元</p>
                </div>
            </a>
        </li>
        <li class="set_item" product-id="0">
            <a >
                <img src="images/weeding_sets_img.jpg" alt="">
                <div class="info flexbox v_center">
                    <p class="flex1">紫禁之巅</p>
                    <p class="price"><strong>8888</strong>元</p>
                </div>
            </a>
        </li>
    </ul>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        $("li").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/set_detail');?>&product_id="+ $(this).attr("product-id") +"&category=<?php echo $_GET['category']?>";
        });
    })
</script>
</body>
</html>