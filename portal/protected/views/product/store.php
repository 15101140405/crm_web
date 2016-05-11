<!DOCTYPE html>
<html>

<head>
    <title><?php echo $hotel_name?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/base1.css">
    <link rel="stylesheet" type="text/css" href="css/order.css">
    <link rel="stylesheet" type="text/css" href="css/store.css">
</head>

<body>
    <!--导航-->
    <nav class="fixed_nav" id="main_nav">
        <ul>
            <li id="product_store" class="active">
                <span></span>
                <p class="cat_name">产品库</p>
            </li>
            <li id="index">
                <span></span>
                <p class="cat_name">档期</p>
            </li>
            <li id="order">
                <span></span>
                <p class="cat_name">订单</p>
            </li>
            <li id="finance_report">
                <span></span>
                <p class="cat_name">我的业绩</p>
            </li>
        </ul>
    </nav>

    <article class="store_container">
        <!--套系-->
        <section class="store_module">
            <div class="flexbox v_center h2_box" style="height:2rem;">
                <span></span>
                <h2>套系</h2>
                <span></span>
            </div>
            <ul class="module_con store_set_list flexbox">
                <li>
                    <a class="flexbox v_center" style="height:3.8rem;" href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=2&category=2&from=wedding_feast">
                        <img class="icon" src="images/set01.png" alt="">
                        <div class="info flex1">
                            <p>婚宴</p>
                            <p>20元起</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="flexbox v_center" style="height:3.8rem;" href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=2&category=1&from=meeting_feast">
                        <img class="icon" src="images/set02.png" alt="">
                        <div class="info flex1">
                            <p>会议餐</p>
                            <p>20元起</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="flexbox v_center" style="height:3.8rem;" href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=22&category=2&from=set">
                        <img class="icon" src="images/set03.png" alt="">
                        <div class="info flex1">
                            <p>婚礼套系</p>
                            <p>20元起</p>
                        </div>
                    </a>
                </li>
                <li>
                    <a class="flexbox v_center" style="height:3.8rem;" href="<?php echo $this->createUrl("product/set_list");?>&supplier_type_id=22&category=1&from=set">
                        <img class="icon" src="images/set04.png" alt="">
                        <div class="info flex1">
                            <p>会议套系</p>
                            <p>20元起</p>
                        </div>
                    </a>
                </li>
            </ul>
        </section>
        <!--服务人员-->
        <section class="store_module">
            <div class="flexbox v_center h2_box"  style="height:2rem;">
                <span></span>
                <h2>服务人员</h2>
                <span></span>
            </div>
            <ul class="module_con store_man_list flexbox">
                <li>
                    <a href="<?php echo $this->createUrl('service/list');?>&type_id=3&category=2"><img src="images/man02.png" alt="">
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('service/list');?>&type_id=5&category=2"><img src="images/man01.png" alt="">
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('service/list');?>&type_id=4&category=2"><img src="images/man03.png" alt="">
                    </a>
                </li>
                <li>
                    <a href="<?php echo $this->createUrl('service/list');?>&type_id=6&category=2"><img src="images/man04.png" alt="">
                    </a>
                </li>
            </ul>
        </section>

        <!--婚礼定制-->
        <section class="store_module">
            <div class="flexbox v_center h2_box"  style="height:2rem;">
                <span></span>
                <h2>婚礼定制</h2>
                <span></span>
            </div>
            <ul class="module_con diy_list">
                <li>
                    <a class="flexbox v_center" style="height:4rem;" href="javascript:;">
                        <img src="images/set05.png" alt="">
                        <div class="info flex1">
                            <p>婚礼定制</p>
                            <p>婚礼定制婚礼定制婚礼定制</p>
                        </div>
                    </a>
                </li>
            </ul>
        </section>
    </article>

    <script type="text/javascript" src='js/zepto.min.js'></script>
    <script src="js/common.js"></script>
    <script type="text/javascript" src='js/nav.js'></script>

<script>
    $(function () {

        //导航
        $("#product_store").on("click",function(){
            location.href = "<?php echo $this->createUrl('product/store');?>&code=&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
        });
        $("#index").on("click",function(){
            location.href = "<?php echo $this->createUrl('order/index');?>&from=";
        });
        $("#order").on("click",function(){
            location.href = "<?php echo $this->createUrl('order/order');?>";
        });
        $("#finance_report").on("click",function(){            
            location.href = "<?php echo $this->createUrl('report/financereport');?>";
        });


        if("<?php echo $user_type ?>" == "1"){
            var html ='<li id="order_count"><span></span><p class="cat_name">订单统计</p></li><li id="hotel_finance_report"><span></span><p class="cat_name">本店财报</p></li>';
            $("#order").remove();
            $("#finance_report").remove();
            $("#index").after(html);
            $("#order_count").on("click",function(){
                location.href = "<?php echo $this->createUrl('report/order_report');?>";
            });
            $("#hotel_finance_report").on("click",function(){
                location.href = "<?php echo $this->createUrl('report/hotel_finance_report');?>";
            });
        };
        

    })
</script>
</body>

</html>