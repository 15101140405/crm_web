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
<?php foreach ($product_data as $key => $value) { ?>
        <li class="set_item" product-id="<?php echo $value['id']?>">
            <a>
                <img src="<?php echo $value['img_url']?>" alt="">
                <div class="info flexbox v_center">
                    <p class="flex1"><?php echo $value['name']?></p>
                    <p class="price"><strong><?php echo $value['price']?></strong><?php echo $value['unit']?></p>
                </div>
            </a>
        </li>
<?php }?>
    </ul>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        $("li").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/set_detail');?>&product_id="+ $(this).attr("product-id") +"&category=&from=";
        })
    })
</script>
</body>
</html>