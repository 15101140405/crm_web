<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="css/upscroller.css">
    <link rel="stylesheet" href="css/my-app.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/swiper.min.css">
</head>
<body>
<article style="position: relative;top: -45px;">
    <div class="tool_bar" style="line-height: 3rem;">
        <div class="l_btn" data-icon="&#xe679;" style="margin-top: 10px;"></div>
        <h6  style="text-align: center;font-size: 2rem;"><?php echo $arr_order_data['order_name']; ?></h6>
        <h6  style="text-align: center;font-size: 1.2rem;">活动日期：<?php echo $arr_order_data['order_date']; ?></h6>
    </div>
    <ul class="m-index-list" id="page_list" style="margin-bottom: 10px;">
        <li class="card list_more2" category="appear" status="进行中" id='follow'>
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 1.8rem;text-align: left;margin-left: 10px;">跟进情况 <span style="color:#b6babf;font-size:1rem;"> [<?php echo $planner; ?>]</span></h6>
            <div class="m-money clear" style="display:inline">
                <div style="display:inline-block;float:left;margin-left:15%;border-right:1px solid #ebebeb;width:35%">
                    <p class="m-money-per " >进店面谈</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;"><?php echo $in_door;?> <i class="m-money-per "> 次</i></p>
                </div>
                <div style="display:inline-block;float:right;margin-right:15%;">
                    <p class="m-money-per ">跟单</p>
                    <p class="m-money-num " style="color: #37CB58;font-size: 1.8rem;"><?php echo $out_door;?> <i class="m-money-per "> 次</i></p>
                </div>
            </div>
        </li>
    </ul>
    <nav id="action">
        <ul>
            <li style="width:50%" id="print">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/gendan.png"/>
                    </div>
                    <p class="cat_name">打印报价单</p>
                </a>
            </li>
            <li style="width:50%" id="checkout">
                <a href="#">
                    <div class="cat_icon">
                        <img src="images/cehuashi.png"/>
                    </div>
                    <p class="cat_name" id="checkout_name">申请结算</p>
                </a>
            </li>
        </ul>
    </nav>
    <!-- 基本信息 -->
    <div class="ulist_module" style="margin-top:10px;">
        <ul class="ulist">
            <li class="ulist_item list_more" id="detailinfo">
                <span class="big_font">基本信息</span>
                <!-- <a class="singup right btn_disable" id="print" href="">打印</a> -->
                <!-- <a class="singup right btn_red" id="examined" href="">审批通过</a>
                <a class="singup right btn_disable" id="examining" href="">审批中</a>
                <a class="singup right" id="non_examine" href="">核算审批</a> -->
            </li>
            <li class="ulist_item overflow1">客户名称：<span class="overflow2"><?php echo $arr_order_data['order_name']; ?><span></li>
            <li class="ulist_item">联 系 人：<?php echo $linkman; ?></li>
            <li class="ulist_item">活动日期：<?php echo $arr_order_data['order_date']; ?></li>
        </ul>
    </div>
    <div class="ulist_module" style="margin-top:10px;">
        <ul class="ulist">
            <li class="ulist_item list_more" id="select_planner">统 筹 师：<?php echo $planner; ?></li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more department" id="channel">
                <span class="label">推   单：</span>
                <div class="align_r1 dep_content" id="channel_content"><?php echo $select_reference['name'] ?></div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="feast_discount">
                <span class="label">餐饮折扣：</span>
                <div class="align_r1 dep_content" id="feast_discount"><?php echo $arr_order_data['feast_discount']; ?>折</div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="other_discount">
                <span class="label">其他折扣：</span>
                <div class="align_r1 dep_content" id="other_discount"><?php echo $arr_order_data['other_discount']; ?>折</div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="changefree">
                <span class="label">免零：</span>
                <div class="align_r1 dep_content" id="other_discount"><?php echo $arr_order_data['cut_price']; ?>元</div>
            </li>
        </ul>
    </div>
    <!-- 回款信息 -->
    <!-- 回款信息 -->
    <div class="table_module">
        <h4 class="module_title list_more" id="payment">回款纪录</h4>
        <table class="mar_b10">
            <tbody>
            <tr>
                <td>定金</td>
                <td>&yen;<?php echo $arr_order_data['feast_deposit']; ?></td>
                <!-- <td><?php /*echo *//*$return_bill['first']['order_date'];*/ ?></td> -->
            </tr>
            <tr>
                <td>中期款</td>
                <td>&yen;<?php echo $arr_order_data['medium_term']; ?></td>
                <!-- <td><?php /*echo *//*$return_bill['second']['order_date'];*/ ?></td> -->
            </tr>
            <tr>
                <td>尾款</td>
                <td>&yen;<?php echo $arr_order_data['final_payments']; ?></td>
                <!-- <td><?php /*echo*/ /*$return_bill['third']['order_date'];*/ ?></td> -->
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 餐饮 -->
    <div class="bill_item_module">
        <h4 class="module_title list_more" id="feast">会议餐</h4>
        <div class="ulist_module">
    <?php
        if (!empty($arr_wed_feast)) {
    ?>
            <ul class="ulist menu_list">
                <li class="ulist_item">
                    <div class="dishes">
                        <p class="name"><?php echo $arr_wed_feast['name']; ?></p>
                        <p class="desc">
                        </p>
                    </div>
                    <div>
                        <p class="price">&yen;<?php echo $arr_wed_feast['unit_price'] . $arr_wed_feast['unit']; ?></p>
                        <p class="fee"><?php echo $arr_wed_feast['service_charge_ratio'].'%'; ?>服务费</p>
                    </div>
                    <span class="account">X <?php echo $arr_wed_feast['table_num']; ?></span>
                </li>
            </ul>
    <?php
        }
    ?>
            <!-- <p class="remark"><i class="t_green">[备注]</i> </p> -->
    <?php
        if(!empty($arr_wed_feast) && $arr_wed_feast['total_price'] != 0){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_wed_feast['total_price']; ?></p>
                <i class="profits">毛利：&yen;<?php echo $arr_wed_feast['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_wed_feast['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }else{
    ?>
            <div class="subtotal">
                <p>小计：&yen;0</p>
                <i class="profits">毛利：&yen;0</i>
                <i class="profits">毛利率：0%</i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
    

        <!-- 场地费 -->
    
    <div class="bill_item_module">
        <h4 class="module_title list_more" id="changdifei">场地费</h4>
        <div class="ulist_module">
<?php
    if (!empty($arr_changdi_fee)) {
?>
            <ul class="ulist menu_list">
                <li class="ulist_item">
                    <div class="dishes">
                        <p class="name"><?php echo $arr_changdi_fee['name']; ?></p>
                        <p class="desc">
                        </p>
                    </div>
                    <div>
                        <p class="price">&yen;<?php echo $arr_changdi_fee['unit_price'] . $arr_changdi_fee['unit']; ?></p>
                    </div>
                    <span class="account">X <?php echo $arr_changdi_fee['amount']; ?></span>
                </li>
            </ul>
    <?php
        };
    ?>
    <?php
        if(!empty($arr_changdi_fee) && $arr_changdi_fee['total_price'] != 0){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_changdi_fee['total_price']; ?></p>
                <i class="profits">毛利：&yen;<?php echo $arr_changdi_fee['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_changdi_fee['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }else{
    ?>
            <div class="subtotal">
                <p>小计：&yen;0</p>
                <i class="profits">毛利：&yen;0</i>
                <i class="profits">毛利率：0%</i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
    
    <!--灯光-->
    
    <div class="bill_item_module">
        <h4 class="module_title list_more" id="lighting">灯光</h4>
        <div class="ulist_module">
    <?php
        if (!empty($arr_light)) {
    ?>
            <ul class="ulist menu_list">
                <?php
                    foreach ($arr_light as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $light[$key1] = $value1;
                    }
                        ?>
                    <li class="ulist_item">
                        <div class="dishes">
                            <p class="name"><?php echo $light['name']; ?></p>
                        </div>
                        <div>
                            <p class="price">&yen;<?php echo $light['unit_price'] . $light['unit']; ?></p>
                        </div>
                        <span class="account">X <?php echo $light['amount']; ?></span>
                    </li>
                <?php } ?>
            </ul>
    <?php
        };
    ?>
    <?php
        if($arr_light_total['total_price'] != 0){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_light_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_light_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_light_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }else{
    ?>
            <div class="subtotal">
                <p>小计：&yen;0</p>
                <i class="profits">毛利：&yen;0</i>
                <i class="profits">毛利率：0%</i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
    
    <!--视频-->
    
    <div class="bill_item_module">
        <h4 class="module_title list_more" id="screen">视频</h4>
        <div class="ulist_module">
    <?php
        if (!empty($arr_video)) {
    ?>
            <ul class="ulist menu_list">
                <?php
                    foreach ($arr_video as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $video[$key1] = $value1;
                    }
                        ?>
                    <li class="ulist_item">
                        <div class="dishes">
                            <p class="name"><?php echo $video['name']; ?></p>
                        </div>
                        <div>
                            <p class="price">&yen;<?php echo $video['unit_price'] . $video['unit']; ?></p>
                        </div>
                        <span class="account">X <?php echo $video['amount']; ?></span>
                    </li>
                <?php } ?>
            </ul>
    <?php
        };
    ?>
    <?php
        if($arr_video_total['total_price'] != 0){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_video_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_video_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_video_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }else{
    ?>
            <div class="subtotal">
                <p>小计：&yen;0</p>
                <i class="profits">毛利：&yen;0</i>
                <i class="profits">毛利率：0%</i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
    
    <?php
        if (!empty($arr_total)) {
    ?>
    <div class="bottom_fixed_bar">
        <p class="total_right">总价：<i class="t_green">&yen;<?php echo $arr_total['total_price'] ?></i></p>
        <div class="total_left">
            <i><p>利润：&yen;<?php echo $arr_total['gross_profit'] ?></i>
            <i><p>毛利率：<?php echo sprintf("%01.2f", $arr_total['gross_profit_rate']*100).'%' ?></i>
        </div>
    </div>
    <?php
        }else{
    ?>
            <div class="bottom_fixed_bar">
                <p class="total_right">总价：<i class="t_green">&yen;0</i></p>
                <div class="total_left">
                    <i><p>利润：&yen;0</i>
                    <i><p>毛利率：&yen;0</i>
                </div>
            </div>
    <?php
        }
    ?>
    <div class="bottom_fixed_bar" id='bottom' style="position: fixed;bottom: 61px;">
        <div class="r_btn" id="agree">同意</div>
        <div class="r_btn " id="refuse" style='background-color:#da0f22;'>拒绝</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //返回
        console.log($('.card'));
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/my");?>&t=plan&code=";
        });

        //打印
        $("#print").on("click",function(){
            location.href="<?php echo $this->createUrl("order/orderprint", array("order_id" => $_GET['order_id'],"type" => "meeting"));?>";
        });

        var order_status = <?php echo $arr_order_data['order_status']?>;

        if(order_status != 5 && order_status != 6){
            //跳推单页面
            $("#channel").on("click", function () {
                location.href = "<?php echo $this->createUrl("plan/chooseChannel", array("from" => "meeting", "order_id" => $_GET['order_id']));?>";
            })

            //跳转详细信息
            $("#detailinfo").on("click",function(){
                location.href="<?php echo $this->createUrl("meeting/detailinfo", array("order_id" => $_GET['order_id']));?>"
            })

            //跳折扣页面
            $("#feast_discount").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from1" => "meeting","order_id" => $_GET['order_id'],"type" => "feast"));?>";
            });
            $("#other_discount").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from1" => "meeting","order_id" => $_GET['order_id'],"type" => "wedding_other"));?>";
            });

            //跳免零页面
            $("#changefree").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/changefree", array("from1" => "meeting","order_id" => $_GET['order_id']));?>";
            });

            //跳收款列表
            $("#payment").on("click",function(){
                location.href="<?php echo $this->createUrl("finance/cashierlist", array("from" => "meeting", "tab" => "","order_id" => $_GET['order_id']));?>"
            })

            //跳转产品分类
            $("#feast").on("click", function () {
                location.href = "<?php echo $this->createUrl("meeting/feast", array("from" => "meeting", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#changdifei").on("click", function () {
                location.href = "<?php echo $this->createUrl("meeting/changdifei", array("from" => "meeting", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#lighting").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/lightingScreen", array("from" => "meeting", "tab" => "lighting","order_id" => $_GET['order_id']));?>";
            });

            $("#screen").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/lightingScreen", array("from" => "meeting", "tab" => 'screen',"order_id" => $_GET['order_id']));?>";
            });

            $("#service").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/ServicePersonnel", array("from" => "meeting", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#decoration").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/decoration", array("from" => "meeting", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#graphic").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/graphicfilm", array("from" => "meeting", "tab" => "graphic","order_id" => $_GET['order_id']));?>";
            });
            $("#film").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/graphicfilm", array("from" => "meeting", "tab" => "film","order_id" => $_GET['order_id']));?>";
            });

            $("#drinks").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/drinksCar", array("from" => "meeting", "tab" => "drinks","order_id" => $_GET['order_id']));?>";
            });

            $("#car").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/drinksCar", array("from" => "meeting", "tab" => "car","order_id" => $_GET['order_id']));?>";
            });

            $("#other").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/planother", array("from" => "meeting", "tab" => "other","order_id" => $_GET['order_id']));?>";
            });
        }else{
            $(".list_more").removeClass('list_more');
        }

        //跳转到跟单页面
        $("#follow").on('click',function(){
            location.href='<?php echo $this->createUrl("order/orderinfofollow");?>&from=meeting&order_id=<?php echo $_GET["order_id"]?>';
        });

        //结算
        var order_status=<?php echo $arr_order_data['order_status'];?>;
        /*alert(order_status);*/
        if(order_status == 5){$("#checkout_name").html("结算中")};
        if(order_status == 6){$("#checkout_name").html("已完成")};
        $("#checkout").on("click",function(){
            if(order_status == 4){
                $.post("<?php echo $this->createUrl('order/checkoutpost');?>",{type:'meeting',order_id:<?php echo $_GET['order_id']?>},function(){
                    location.reload();
                });
            }else if(order_status == 5){
                alert("结算中，请耐心等待！");
            }else if(order_status ==0 || order_status ==1 || order_status ==2 || order_status ==3){
                alert("订单收取尾款后，才能申请结算！");
            }
        });

        //order_status = 5 && 访问者为 财务 时 , 出现：同意／拒绝 按钮
        var order_status = <?php echo $arr_order_data['order_status'];?>;
<?php 
    $newstr = rtrim($user_department_list, "]");
    $newstr = ltrim($newstr, "[");
    $arr_type = explode(",",$newstr);
    $t = 0;
    foreach ($arr_type as $key => $value) {
        if($value == 5){
            $t++;
        }
    };
    if($t == 0){//访问者不在财务部门
?>
        $("#bottom").remove();
<?php
    }else{//访问者在财务部门
?>
        $("#action").remove();
        $("#page_list").remove();
        if(order_status != 5){$("#bottom").remove();};
        //点击同意结算
        $("#agree").on("click",function(){
            $.post("<?php echo $this->createUrl('order/checkoutagree');?>",{type:'design',order_id:<?php echo $_GET['order_id']?>,final_price:<?php echo $arr_total['total_price']?>,final_cost:<?php echo $arr_total['total_cost']?>,final_profit:<?php echo $arr_total['gross_profit']?>,final_profit_rate:<?php echo $arr_total['gross_profit_rate']?>,final_payment:<?php echo $payment_total;?>},function(){
                location.reload();
            });
        });
        //点击拒绝结算
        $("#refuse").on("click",function(){
            
            $.post("<?php echo $this->createUrl('order/checkoutrefuse');?>",{type:'meeting',order_id:<?php echo $_GET['order_id']?>},function(){
                location.reload();
            });
        });

        
<?php 
    }
?>

    //选择统筹师
    $("#select_planner").on("click",function(){
        location.href = "<?php echo $this->createUrl("order/transition");?>&from=meeting&order_id=<?php echo $_GET['order_id']?>&type=planner";
    });
    })
</script>
</body>
</html>
