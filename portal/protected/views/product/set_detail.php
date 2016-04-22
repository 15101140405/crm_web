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
    <!-- <div id="iSlider-effect-wrapper">
        <div id="animation-effect" class="iSlider-effect"></div>
    </div> -->
    <ul class="set_list">
        <?php foreach ($img as $key => $value) { ?>
        <li class="set_item">
            <img src="<?php if (!empty($value)) {echo $value['img_url'];}?>" alt="">
        </li>
        <?php }?>
    </ul>
    <div class="fixed_bot_btn flexbox v_center">
        <div class="flex1 fixed_content" style="width: 40%;float: left;">
            <p class="now_price">&yen;<strong><?php echo $supplier_product['unit_price']?></strong>
            </p>
            <!-- <del class="original_price">&yen;<em>160</em></del> -->
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

        /*var picList = [
        {
            width: 150,
            height: 207,
            content: "images/1.jpg",
        },
        {
            width: 150,
            height: 207,
            content: "images/2.jpg",
        },
        {
            width: 150,
            height: 207,
            content: "images/3.jpg",
        },
        {
            width: 150,
            height: 207,
            content:"images/5.jpg"
        },
        {
            width: 150,
            height: 207,
            content:"images/6.jpg"
        },
        {
            width: 300,
            height: 414,
            content:"images/7.jpg"
        },
        {
            width: 150,
            height: 207,
            content:"images/8.jpg"
        },
        {
            width: 150,
            height: 207,
            content:"images/9.jpg"
        }
        ];
        var menu = document.getElementById('menu-select').children;

        function clickMenuActive(target) {

            for (var i = 0; i < menu.length; i++) {
                menu[i].className = '';
            }

            target.className = 'on';
            
        };

        menu[0].onclick = function() {

            clickMenuActive(this);
            islider1._opts.animateType = this.innerHTML;
            islider1.reset();
        };

        menu[1].onclick = function() {

            clickMenuActive(this);
            islider1._opts.animateType = this.innerHTML;
            islider1.reset();
        };

        menu[2].onclick = function() {

            clickMenuActive(this);
            islider1._opts.animateType = this.innerHTML;
            islider1.reset();
        };

        menu[3].onclick = function() {

            clickMenuActive(this);
            islider1._opts.animateType = this.innerHTML;
            islider1.reset();
        };*/
    })
</script>
</body>
</html>