<?php
$arr_locate = array(
                'img' => 'meeting_layout.jpg',

            );

?>
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
    <link href="css/staff_management.css" rel="stylesheet" type="text/css"/>

</head>
<body>
<article style='position: relative;bottom: 100px;top: 1px;'>
    <div class="tool_bar">
        <h2 class="page_title">报价单</h2>

    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="detailinfo">
                <span class="big_font">婚礼信息</span>
                <!-- <a href='' class="singup right btn_disable" id="print" href="">打印</a> -->
                <!-- <a class="singup right btn_red" id="examined" href="">审批通过</a>
                <a class="singup right btn_disable" id="examining" href="">审批中</a>
                <a class="singup right" id="non_examine" href="">核算审批</a> -->
            </li>
            <li class="ulist_item">婚礼日期：<?php echo $arr_order_data['order_date']; ?></li>
            <li class="ulist_item">新人姓名：<?php echo $arr_order_data['order_name']; ?></li>
            <li class="ulist_item">策 划 师：<?php echo $designer; ?></li>
            <li class="ulist_item">统 筹 师：<?php echo $planner; ?></li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more department" id="channel">
                <span class="label">推   单：</span>
                <div class="align_r1 dep_content" id="channel_content"><?php echo $select_reference['name']; ?></div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="feast_discount">
                <span class="label">餐饮折扣：</span>
                <div class="align_r1 dep_content"
                     id="discount_content"><?php echo $arr_order_data['feast_discount']; ?>折</div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="other_discount">
                <span class="label">其他折扣：</span>
                <div class="align_r1 dep_content"
                     id="discount_content"><?php echo $arr_order_data['other_discount']; ?>折</div>
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
    <!-- <div class="table_module">
        <h4 class="module_title list_more" id="payment">回款纪录</h4>
        <table class="mar_b10">
            <tbody>
            <tr>
                <td>定金</td>
                <td>&yen;<?php /*echo $arr_order_data['feast_deposit'];*/ ?></td>
                <td><?php /*echo $arr_order_data['first']['order_date'];*/ ?></td>
            </tr>
            <tr>
                <td>中期款</td>
                <td>&yen;<?php /*echo $arr_order_data['medium_term'];*/ ?></td>
                <td><?php /*echo $arr_order_data['second']['order_date']; */?></td>
            </tr>
            <tr>
                <td>尾款</td>
                <td>&yen;<?php /*echo $arr_order_data['final_payments'];*/ ?></td>
                <td><?php /*echo $arr_order_data['third']['order_date']; */?></td>
            </tr>
            </tbody>
        </table>
    </div> -->
    <!-- 餐饮 -->

    <div class="bill_item_module" id="feast">
        <h4 class="module_title list_more">婚宴</h4>
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
                        <p class="fee"><?php echo $arr_wed_feast['service_charge_ratio']; ?>服务费</p>
                    </div>
                    <span class="account">X <?php echo $arr_wed_feast['table_num']; ?></span>
                </li>
            </ul>
    <?php
        }
    ?>
            <!-- <p class="remark"><i class="t_green">[备注]</i> </p> -->
    <?php 
        if(!empty($arr_wed_feast)){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_wed_feast['total_price']; ?></p>
                <i class="profits">毛利：&yen;<?php echo $arr_wed_feast['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_wed_feast['gross_profit_rate']*100).'%'; ?></i>
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
        }
    ?>
    <?php 
        if(!empty($arr_light_total)){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_light_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_light_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_light_total['gross_profit_rate']*100).'%'; ?></i>
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
        }
    ?>
    <?php 
        if(!empty($arr_video_total)){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_video_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_video_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_video_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
    


            <!-- 人员-->
            <div class="bill_item_module">
                <h4 class="module_title list_more" id="service">人员</h4>
                <div class="ulist_module">
                    <ul class="ulist menu_list">
                        <!-- 主持 -->
                        <?php
                        if(!empty($arr_host)){
                            foreach ($arr_host as $key => $value) {
                                foreach ($value as $key1 => $value1) {
                                    $host[$key1] = $value1;
                                }
                            ?>
                            <li class="ulist_item">
                                <div class="dishes">
                                    <p class="name2"><i class="t_gray">[主持] </i>｜<?php echo $host['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price">&yen;<?php echo $host['unit_price'] . $host['unit']; ?></p>
                                </div>
                                <span class="account">X <?php echo $host['amount']; ?></span>
                            </li>
                        <?php 
                            } 
                        }
                        ?>
                        <!-- 摄像 -->
                        <?php
                        if(!empty($arr_camera)){
                            foreach ($arr_camera as $key => $value) {
                                foreach ($value as $key1 => $value1) {
                                    $camera[$key1] = $value1;
                                }
                            ?>
                            <li class="ulist_item">
                                <div class="dishes">
                                    <p class="name2"><i class="t_gray">[摄像] </i>｜<?php echo $camera['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price">&yen;<?php echo $camera['unit_price'] . $camera['unit']; ?></p>
                                </div>
                                <span class="account">X <?php echo $camera['amount']; ?></span>
                            </li>
                        <?php 
                            } 
                        }
                        ?>
                        <!-- 摄影 -->
                        <?php
                        if(!empty($arr_photo)){
                            foreach ($arr_photo as $key => $value) {
                                foreach ($value as $key1 => $value1) {
                                    $photo[$key1] = $value1;
                                }
                            ?>
                            <li class="ulist_item">
                                <div class="dishes">
                                    <p class="name2"><i class="t_gray">[摄影] </i>｜<?php echo $photo['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price">&yen;<?php echo $photo['unit_price'] . $photo['unit']; ?></p>
                                </div>
                                <span class="account">X <?php echo $photo['amount']; ?></span>
                            </li>
                        <?php 
                            } 
                        }
                        ?>
                        <!-- 化妆 -->
                        <?php
                        if(!empty($arr_makeup)){
                            foreach ($arr_makeup as $key => $value) {
                                foreach ($value as $key1 => $value1) {
                                    $makeup[$key1] = $value1;
                                }
                            ?>
                            <li class="ulist_item">
                                <div class="dishes">
                                    <p class="name2"><i class="t_gray">[化妆] </i>｜<?php echo $makeup['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price">&yen;<?php echo $makeup['unit_price'] . $makeup['unit']; ?></p>
                                </div>
                                <span class="account">X <?php echo $makeup['amount']; ?></span>
                            </li>
                        <?php 
                            } 
                        }
                        ?>
                        <!-- 其他 -->
                        <?php
                        if(!empty($arr_other)){
                            foreach ($arr_other as $key => $value) {
                                foreach ($value as $key1 => $value1) {
                                    $other[$key1] = $value1;
                                }
                            ?>
                            <li class="ulist_item">
                                <div class="dishes">
                                    <p class="name2"><i class="t_gray">[其他] </i>｜<?php echo $other['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price">&yen;<?php echo $other['unit_price'] . $other['unit']; ?></p>
                                </div>
                                <span class="account">X <?php echo $other['amount']; ?></span>
                            </li>
                        <?php 
                            } 
                        }
                        ?>
                    </ul>
    <?php 
        if(!empty($arr_service_total)){
    ?>
                    <div class="subtotal">
                        <p>小计：&yen;<?php echo $arr_service_total['total_price']; ?></p>
                        <i class="profits">利润：&yen;<?php echo $arr_service_total['gross_profit']; ?></i>
                        <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_service_total['gross_profit_rate']*100).'%'; ?></i>
                    </div>
    <?php 
        }
    ?>
                </div>
            </div>

            <!-- 场地布置 -->
            <div class="bill_item_module ">
                <h4 class="module_title list_more" id="decoration">场地布置</h4>
                <div class="ulist_module">
                    <ul class="ulist menu_list">
                        <?php
                        if(!empty($arr_decoration)){
                            foreach ($arr_decoration as $key => $value) {
                                foreach ($value as $key1 => $value1) {
                                    $decoration[$key1] = $value1;
                                }
                            ?>
                        <li class="ulist_item img_item">
                            <div class="img_bar">
                                <img src="images/<?php echo $arr_locate['img']; ?>">
                            </div>
                            <div class="dishes">
                                <p class="name"><?php echo $decoration['name']; ?></p>
                            </div>
                            <div>
                                <p class="price">&yen;<?php echo $decoration['unit_price'] . $decoration['unit']; ?></p>
                            </div>
                            <span class="account">X <?php echo $decoration['amount']; ?></span>
                        </li>
                        <?php 
                            } 
                        }
                        ?>
                    </ul>
    <?php 
        if(!empty($arr_decoration_total)){
    ?>
                    <div class="subtotal">
                        <p>小计：&yen;<?php echo $arr_decoration_total['total_price']; ?></p>
                        <i class="profits">利润：&yen;<?php echo $arr_decoration_total['gross_profit']; ?></i>
                        <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_decoration_total['gross_profit_rate']*100).'%'; ?></i>
                    </div>
    <?php 
        }
    ?>
                </div>
            </div>

    <!--平面设计-->
    
    <div class="bill_item_module">
        <h4 class="module_title list_more" id="graphic">平面设计</h4>
        <div class="ulist_module">
    <?php
        if (!empty($arr_graphic)) {
    ?>
            <ul class="ulist menu_list">
                <?php
                    foreach ($arr_graphic as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $graphic[$key1] = $value1;
                    }
                        ?>
                    <li class="ulist_item">
                        <div class="dishes">
                            <p class="name"><?php echo $graphic['name']; ?></p>
                        </div>
                        <div>
                            <p class="price">&yen;<?php echo $graphic['unit_price'] . $graphic['unit']; ?></p>
                        </div>
                        <span class="account">X <?php echo $graphic['amount']; ?></span>
                    </li>
                <?php } ?>
            </ul>
    <?php
        }
    ?>
    <?php 
        if(!empty($arr_graphic_total)){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_graphic_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_graphic_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_graphic_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
    

    <!--视频设计-->
    
    <div class="bill_item_module">
        <h4 class="module_title list_more" id = "film">视频设计</h4>
        <div class="ulist_module">
    <?php
        if (!empty($arr_film)) {
    ?>
            <ul class="ulist menu_list">
                <?php
                    foreach ($arr_film as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $film[$key1] = $value1;
                    }
                        ?>
                    <li class="ulist_item">
                        <div class="dishes">
                            <p class="name"><?php echo $film['name']; ?></p>
                        </div>
                        <div>
                            <p class="price">&yen;<?php echo $film['unit_price'] . $film['unit']; ?></p>
                        </div>
                        <span class="account">X <?php echo $film['amount']; ?></span>
                    </li>
                <?php } ?>
            </ul>
    <?php
        }
    ?>
    <?php 
        if(!empty($arr_film_total)){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_film_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_film_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_film_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
    

    <!--策划费&杂费-->
    
    <div class="bill_item_module">
        <h4 class="module_title list_more" id="planother">策划费&杂费</h4>
        <div class="ulist_module">
    <?php
        if (!empty($arr_designer)) {
    ?>
            <ul class="ulist menu_list">
                <?php
                    foreach ($arr_designer as $key => $value) {
                        foreach ($value as $key1 => $value1) {
                            $d[$key1] = $value1;
                    }
                        ?>
                    <li class="ulist_item">
                        <div class="dishes">
                            <p class="name"><?php echo $d['name']; ?></p>
                        </div>
                        <div>
                            <p class="price">&yen;<?php echo $d['unit_price'] . $d['unit']; ?></p>
                        </div>
                        <span class="account">X <?php echo $d['amount']; ?></span>
                    </li>
                <?php } ?>
            </ul>
    <?php
        }
    ?>
    <?php 
        if(!empty($arr_designer_total)){
    ?>
            <div class="subtotal">
                <p>小计：&yen;<?php echo $arr_designer_total['total_price']; ?></p>
                <i class="profits">利润：&yen;<?php echo $arr_designer_total['gross_profit']; ?></i>
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", $arr_designer_total['gross_profit_rate']*100).'%'; ?></i>
            </div>
    <?php
        }
    ?>
        </div>
    </div>
           

    <?php 
        if(!empty($arr_order_total)){
    ?>
            <div class="bottom_fixed_bar">
                <p class="total_right">总价：<i class="t_green">&yen;<?php echo $arr_order_total['total_price'] ?></i></p>
                <div class="total_left">
                    <i><p>利润：&yen;<?php echo $arr_order_total['gross_profit'] ?></i>
                    <i><p>毛利率：&yen;<?php echo sprintf("%01.2f", $arr_order_total['gross_profit_rate']*100).'%'?></i>
                </div>
            </div>
    <?php
        }
    ?>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //返回
        $(".l_btn").on("click", function () {
            var from = <?php echo '"'.$_GET['from'].'"'; ?>;
            var order_id = <?php echo $_GET['order_id'];?>;
            /*alert(from);*/
            if( from == 'detail' ){
                location.href = "<?php echo $this->createUrl("design/detail");?>&order_id=" + order_id;
            }else if( from == 'index' ){
                location.href = "<?php echo $this->createUrl("order/index");?>&from=bill&code=<?php if(isset($_SESSION['code'])){echo $_SESSION['code'];};?>&this_order=" + order_id;
            }
            
        });

        var order_status = <?php echo $arr_order_data['order_status']?>;

        if(order_status != 5 && order_status != 6){
            //跳推单页面
            $("#channel").on("click", function () {
                location.href = "<?php echo $this->createUrl("plan/chooseChannel", array("from" => "design", "order_id" => $_GET['order_id']));?>";
            })

            //打印
            $("#print").on("click",function(){
                location.href="<?php echo $this->createUrl("print/designbill", array("order_id" => $_GET['order_id']));?>";
            });

            //跳转详细信息
            $("#detailinfo").on("click",function(){
                location.href="<?php echo $this->createUrl("plan/detailinfo", array("order_id" => $_GET['order_id']));?>"
            })

            //跳折扣页面
            $("#feast_discount").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from" => $_GET['from'],"from1" => "design","order_id" => $_GET['order_id'],"type" => "feast"));?>";
            });
            $("#other_discount").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from" => $_GET['from'],"from1" => "design","order_id" => $_GET['order_id'],"type" => "wedding_other"));?>";
            });

            //跳免零页面
            $("#changefree").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/changefree", array("from" => $_GET['from'],"from1" => "design","order_id" => $_GET['order_id']));?>";
            });

            //跳收款列表
            $("#payment").on("click",function(){
                location.href="<?php echo $this->createUrl("finance/cashierlist", array("from" => "bill", "tab" => "","order_id" => $_GET['order_id']));?>"
            })

            //跳转产品分类
            $("#feast").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/feast", array("from" => "bill", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#lighting").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/lightingScreen", array("from" => "bill", "tab" => "lighting","order_id" => $_GET['order_id']));?>";
            });
            $("#screen").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/lightingScreen", array("from" => "bill", "tab" => 'screen',"order_id" => $_GET['order_id']));?>";
            });

            $("#service").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/ServicePersonnel", array("from" => "bill", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#decoration").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/decoration", array("from" => "bill", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#graphic").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/graphicfilm", array("from" => "bill", "tab" => "graphic","order_id" => $_GET['order_id']));?>";
            });
            $("#film").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/graphicfilm", array("from" => "bill", "tab" => "film","order_id" => $_GET['order_id']));?>";
            });

            $("#dress").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/dressAppliance", array("from" => "bill", "tab" => "dress","order_id" => $_GET['order_id']));?>";
            });
            $("#appliance").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/dressAppliance", array("from" => "bill", "tab" => "appliance","order_id" => $_GET['order_id']));?>";
            });

            $("#drinks").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/drinksCar", array("from" => "bill", "tab" => "drinks","order_id" => $_GET['order_id']));?>";
            });
            $("#car").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/drinksCar", array("from" => "bill", "tab" => "car","order_id" => $_GET['order_id']));?>";
            });
            
            $("#plan").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/planother", array("from" => "bill", "tab" => "plan","order_id" => $_GET['order_id']));?>";
            });

            $("#other").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/planother", array("from" => "bill", "tab" => "other","order_id" => $_GET['order_id']));?>";
            });
        }else{
            $(".list_more").removeClass('list_more');
        }

        

        //判断订单状态，改变审批按钮
        <?php     //background data
        $order_state = "examined";?>
        var order_state = "<?php echo $order_state;?>"    //php取数
        if (order_state == "non_examine") {
            $("#examining").addClass("hid");
            $("#examined").addClass("hid");
        } else if (order_state == "examining") {
            $("#non_examine").addClass("hid");
            $("#examined").addClass("hid");
        } else if (order_state == "examined") {
            $("#non_examine").addClass("hid");
            $("#examining").addClass("hid");
        }
        ;

    });


</script>
</body>
</html>
