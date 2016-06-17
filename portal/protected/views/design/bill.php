<?php
$arr_locate = array(
                'img' => 'meeting_layout.jpg',

            );

?>

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
    <link rel="stylesheet" href="css/upscroller.css">
    <link rel="stylesheet" href="css/my-app.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/public.css">
    <link rel="stylesheet" href="css/swiper.min.css">
</head>
<body>
    
<article style='position: relative;top: -45px;'>
    <div class="tool_bar" style="line-height: 3rem;">
        <!-- <div class="l_btn" data-icon="&#xe679;" style="margin-top: 10px;"></div> -->
        <h6  style="text-align: center;font-size: 2rem;"><?php echo $arr_order_data['order_name']; ?></h6>
        <h6  style="text-align: center;font-size: 1.2rem;">婚礼日期：<?php echo $arr_order_data['order_date']; ?></h6>
        <div class="r_btn" id="del" style="font-size: 1.5rem;width: 3rem;">删除订单</div>
    </div>
    <ul class="m-index-list" id="page_list" style="margin-bottom: 10px;">
        <li class="card list_more" category="appear" status="进行中" id='follow'>
            <h6 class="m-list-tit" style="color:#37CB58;font-size: 1.8rem;text-align: left;margin-left: 10px;">跟进情况 <span style="color:#b6babf;font-size:1rem;"> [<?php echo $designer; ?>]</span></h6>
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
    <div class="ulist_module" style="margin-top:10px;">
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
        </ul>
    </div>
    <div class="ulist_module" style="margin-top:10px;">
        <ul class="ulist">
            <li class="ulist_item list_more" id="select_desigener">策 划 师：<?php echo $designer; ?></li>
            <li class="ulist_item list_more" id="select_planner">统 筹 师：<?php echo $planner; ?></li>
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
    <div class="table_module">
        <h4 class="module_title list_more" id="payment">回款纪录</h4>
        <table class="mar_b10">
            <tbody>
            <tr>
                <td>定金</td>
                <td>&yen;<?php echo $payment_data['feast_deposit']; ?></td>
                <td><?php /*echo $arr_order_data['first']['order_date'];*/ ?></td>
            </tr>
            <tr>
                <td>中期款</td>
                <td>&yen;<?php echo $payment_data['medium_term']; ?></td>
                <td><?php /*echo $arr_order_data['second']['order_date'];*/ ?></td>
            </tr>
            <tr>
                <td>尾款</td>
                <td>&yen;<?php echo $payment_data['final_payments']; ?></td>
                <td><?php /*echo $arr_order_data['third']['order_date'];*/ ?></td>
            </tr>
            </tbody>
        </table>
    </div>
    <!-- 餐饮 -->
<?php if($t == 0){?>
    <div class="bill_item_module" id="feast">
        <h4 class="module_title list_more">婚宴</h4>
        <div class="ulist_module">
    <?php
        if (!empty($arr_wed_feast)) {
    ?>
            <ul class="ulist menu_list">
            <?php foreach ($wed_feast as $key => $value) {?>
                <li class="ulist_item">
                    <div class="dishes">
                        <p class="name"><?php echo $value['name']; ?></p>
                        <p class="desc">
                        </p>
                    </div>
                    <div>
                        <p class="price" style="width:150px;">&yen;<?php echo $value['actual_price']; ?></p>
                    </div>
                    <span class="account">X <?php echo $value['unit']; ?></span>
                </li>
            <?php }?>
            </ul>
            <div class="table_module" style="border-top: 1px solid #eee;">
                <table class="mar_b10">
                    <tbody>
                    <tr>
                        <td>桌数</td>
                        <td><?php echo $arr_wed_feast['table_num']; ?> 桌</td>
                        <td><?php /*echo $arr_order_data['first']['order_date'];*/ ?></td>
                    </tr>
                    <tr>
                        <td>服务费</td>
                        <td><?php echo $arr_wed_feast['service_charge_ratio']; ?>%</td>
                        <td><?php /*echo $arr_order_data['second']['order_date'];*/ ?></td>
                    </tr>
                    <tr>
                        <td>备注</td>
                        <td><?php echo $arr_wed_feast['remark']; ?></td>
                        <td><?php /*echo $arr_order_data['third']['order_date'];*/ ?></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- <p class="fee">服务费：</p>
            <p class="fee">备注：</p>
            <p class="fee">桌数：</p> -->
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
                <i class="profits">毛利率：<?php echo sprintf("%01.2f", ($arr_wed_feast['gross_profit']/$arr_wed_feast['total_price'])*100).'%'; ?></i>
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
                                    <p class="name2" style="width:94%"><i class="t_gray">[主持] </i>｜<?php echo $host['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price" style="margin-left:160px;width:90px;">&yen;<?php echo $host['unit_price'] . $host['unit']; ?></p>
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
                                    <p class="name2" style="width:94%"><i class="t_gray">[摄像] </i>｜<?php echo $camera['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price" style="margin-left:160px;width:90px;">&yen;<?php echo $camera['unit_price'] . $camera['unit']; ?></p>
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
                                    <p class="name2" style="width:94%"><i class="t_gray">[摄影] </i>｜<?php echo $photo['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price" style="margin-left:160px;width:90px;">&yen;<?php echo $photo['unit_price'] . $photo['unit']; ?></p>
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
                                    <p class="name2" style="width:94%"><i class="t_gray">[化妆] </i>｜<?php echo $makeup['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price" style="margin-left:160px;width:90px;">&yen;<?php echo $makeup['unit_price'] . $makeup['unit']; ?></p>
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
                                    <p class="name2" style="width:94%"><i class="t_gray">[其他] </i>｜<?php echo $other['name']; ?></p>
                                </div>
                                <div>
                                    <p class="price" style="margin-left:160px;width:80px;">&yen;<?php echo $other['unit_price'] . $other['unit']; ?></p>
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
                            <p class="name" style="width:94%;"><?php echo $d['name']; ?></p>
                        </div>
                        <div>
                            <p class="price" style="width:60px;margin-left:160px;">&yen;<?php echo $d['unit_price'] . $d['unit']; ?></p>
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
<?php }else{?>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="feast_cost">
                <span class="label">餐饮总支出：</span>
                <div class="align_r1 dep_content" id="feast_cost_data"><?php if(isset($arr_wed_feast['total_cost'])){echo sprintf("%.2f", $arr_wed_feast['total_cost']);}else{echo 0;} ?>元</div>
            </li>
        </ul>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item list_more" id="wedding_cost">
                <span class="label">婚礼总支出：</span>
                <div class="align_r1 dep_content" id="wedding_cost_data"><?php if(isset($arr_order_total['total_cost'])){if(isset($arr_wed_feast['total_cost'])){echo sprintf("%.2f", $arr_order_total['total_cost']-$arr_wed_feast['total_cost']);}else{echo sprintf("%.2f", $arr_order_total['total_cost']);};}?>元</div>
            </li>
        </ul>
    </div>
<?php }?>
           

    

<?php 
        if(!empty($arr_order_total)){
    ?>
            <div class="bottom_fixed_bar">
                <p class="total_right">折后总价：<i class="t_green">&yen;<?php echo $arr_order_total['total_price'] ?></i></p>
                <div class="total_left">
                    <i><p>折后利润：&yen;<?php echo $arr_order_total['gross_profit'] ?></i>
                    <i><p>折后毛利率：&yen;<?php echo sprintf("%01.2f", $arr_order_total['gross_profit_rate']*100).'%'?></i>
                </div>
            </div>
    <?php
        }else{
    ?>
            <div class="bottom_fixed_bar">
                <p class="total_right">折后总价：<i class="t_green">&yen;0</i></p>
                <div class="total_left">
                    <i><p>折后利润：&yen;0</i>
                    <i><p>折后毛利率：&yen;0</i>
                </div>
            </div>
    <?php
        }
    ?>
    
</article>
    <div class="bottom_fixed_bar" id='bottom' style="position: fixed;bottom: 61px;">
        <div class="r_btn" id="agree">同意</div>
        <div class="r_btn " id="refuse" style='background-color:#da0f22;'>拒绝</div>
    </div>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {
        //返回
        $(".l_btn").on("click", function () {
            var order_id = <?php echo $_GET['order_id'];?>;
            /*alert(from);*/
                location.href = "<?php echo $this->createUrl("design/detail");?>&order_id=" + order_id;            
        });

        var order_status = <?php echo $arr_order_data['order_status']?>;

        //打印
        $("#print").on("click",function(){
            location.href="<?php echo $this->createUrl("order/orderprint", array("order_id" => $_GET['order_id'],"type" => "design"));?>";
        });

        if(order_status != 5 && order_status != 6){
            //跳推单页面
            $("#channel").on("click", function () {
                location.href = "<?php echo $this->createUrl("plan/chooseChannel", array("from" => "design", "order_id" => $_GET['order_id']));?>";
            })

            //跳转详细信息
            $("#detailinfo").on("click",function(){
                location.href="<?php echo $this->createUrl("plan/detailinfo", array("order_id" => $_GET['order_id']));?>"
            })

            //跳折扣页面
            $("#feast_discount").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from1" => "design","order_id" => $_GET['order_id'],"type" => "feast"));?>";
            });
            $("#other_discount").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/chooseDiscount", array("from1" => "design","order_id" => $_GET['order_id'],"type" => "wedding_other"));?>";
            });

            //跳免零页面
            $("#changefree").on("click", function () {
                location.href = "<?php echo $this->createUrl("order/changefree", array("from1" => "design","order_id" => $_GET['order_id']));?>";
            });

            //跳收款列表
            $("#payment").on("click",function(){
                location.href="<?php echo $this->createUrl("finance/cashierlist", array("from" => "design", "tab" => "","order_id" => $_GET['order_id']));?>"
            })

            //跳转产品分类
            $("#feast").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/feast", array("from" => "design", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#lighting").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/lightingScreen", array("from" => "design", "tab" => "lighting","order_id" => $_GET['order_id']));?>";
            });
            $("#screen").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/lightingScreen", array("from" => "design", "tab" => 'screen',"order_id" => $_GET['order_id']));?>";
            });

            $("#service").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/ServicePersonnel", array("from" => "design", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#decoration").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/decoration", array("from" => "design", "tab" => "","order_id" => $_GET['order_id']));?>";
            });

            $("#graphic").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/graphicfilm", array("from" => "design", "tab" => "graphic","order_id" => $_GET['order_id']));?>";
            });
            $("#film").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/graphicfilm", array("from" => "design", "tab" => "film","order_id" => $_GET['order_id']));?>";
            });

            $("#dress").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/dressAppliance", array("from" => "design", "tab" => "dress","order_id" => $_GET['order_id']));?>";
            });
            $("#appliance").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/dressAppliance", array("from" => "design", "tab" => "appliance","order_id" => $_GET['order_id']));?>";
            });

            $("#drinks").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/drinksCar", array("from" => "design", "tab" => "drinks","order_id" => $_GET['order_id']));?>";
            });
            $("#car").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/drinksCar", array("from" => "design", "tab" => "car","order_id" => $_GET['order_id']));?>";
            });
            
            $("#planother").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/planother", array("from" => "design", "tab" => "plan","order_id" => $_GET['order_id']));?>";
            });

            $("#planother").on("click", function () {
                location.href = "<?php echo $this->createUrl("design/planother", array("from" => "design", "tab" => "other","order_id" => $_GET['order_id']));?>";
            });
        }else{
            $(".list_more").removeClass('list_more');
        }

        //跳转到跟单页面
        $("#follow").on('click',function(){
            location.href='<?php echo $this->createUrl("order/orderinfofollow");?>&from=design&order_id=<?php echo $_GET["order_id"]?>';
        });



        //结算
        var order_status=<?php echo $arr_order_data['order_status'];?>;
        /*alert(order_status);*/
        if(order_status == 5){$("#checkout_name").html("结算中")};
        if(order_status == 6){$("#checkout_name").html("已完成")};
        $("#checkout").on("click",function(){
            if(order_status == 4){
                $.post("<?php echo $this->createUrl('order/checkoutpost');?>",{type:'design',order_id:<?php echo $_GET['order_id']?>},function(retval){
                    alert("申请已发送！");
                    location.href = "<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET['order_id']?>&from=";
                });
            }else if(order_status == 5){
                alert("结算中，请耐心等待！");
            }else if(order_status ==0 || order_status ==1 || order_status ==2 || order_status ==3){
                alert("订单收取尾款后，才能申请结算！");
            }
        });
        

        //order_status = 5 && 访问者为 财务 时 , 出现：同意／拒绝 按钮
        var order_status = <?php echo $arr_order_data['order_status'];?>;
<?php if($t == 0){//访问者不在财务部门 ?>
        $("#bottom").remove();
<?php
    }else{//访问者在财务部门
?>

        if(order_status != 5){$("#bottom").remove();};
        //点击同意结算
        $("#agree").on("click",function(){
    <?php if(!empty($arr_wed_feast)){?>
            var data = {
                type:'design',
                order_id:<?php echo $_GET['order_id']?>,
                final_price:<?php echo $arr_order_total['total_price']?>,
                final_cost:<?php echo $arr_order_total['total_cost']?>,
                final_profit:<?php echo $arr_order_total['gross_profit']?>,
                final_profit_rate:<?php echo $arr_order_total['gross_profit_rate']?>,
                final_payment:<?php echo ($payment_data['feast_deposit']+$payment_data['medium_term']+$payment_data['final_payments']);?>,
                feast_profit:<?php echo $arr_wed_feast['gross_profit']; ?>,
                other_profit:<?php echo sprintf("%.2f", $arr_order_total['gross_profit']-$arr_wed_feast['gross_profit']);?>
            }
    <?php }else{?>
            var data = {
                type:'design',
                order_id:<?php echo $_GET['order_id']?>,
                final_price:<?php echo $arr_order_total['total_price']?>,
                final_cost:<?php echo $arr_order_total['total_cost']?>,
                final_profit:<?php echo $arr_order_total['gross_profit']?>,
                final_profit_rate:<?php echo $arr_order_total['gross_profit_rate']?>,
                final_payment:<?php echo ($payment_data['feast_deposit']+$payment_data['medium_term']+$payment_data['final_payments']);?>,
                feast_profit:0,
                other_profit:<?php echo $arr_order_total['gross_profit']?>
            }
    <?php }?>
            $.post("<?php echo $this->createUrl('order/checkoutagree');?>",data,function(data){
                alert("结算完成！");
                location.href = "<?php echo $this->createUrl("report/order_report");?>";
            });
        });
        //点击拒绝结算
        $("#refuse").on("click",function(){
            
            $.post("<?php echo $this->createUrl('order/checkoutrefuse');?>",{type:'meeting',order_id:<?php echo $_GET['order_id']?>},function(){
                alert('已拒绝结算！')
                location.href = "<?php echo $this->createUrl("report/order_report");?>";
            });
        });

        $("#action").remove();
        $("#page_list").remove();
<?php 
    }
?>        

        //返回
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/order");?>&account_id=<?php echo $_SESSION['account_id']?>&code=&t=plan&department=";
        });

        //选择策划师
        $("#select_desigener").on("click",function(){
            location.href = "<?php echo $this->createUrl("order/transition");?>&from=wedding&order_id=<?php echo $_GET['order_id']?>&type=designer";
        });

        //选择统筹师
        $("#select_planner").on("click",function(){
            location.href = "<?php echo $this->createUrl("order/transition");?>&from=wedding&order_id=<?php echo $_GET['order_id']?>&type=planner";
        });

        //删除
        $("#del").on("click",function(){
            $.post("<?php echo $this->createUrl('order/delorder');?>",{'order_id':<?php echo $_GET['order_id']?>},function(retval){
                location.href = "<?php echo $this->createUrl('order/order');?>"; 
            });
        });

        //输入总成本
        $("#feast_cost").on("click",function(){
            location.href="<?php echo $this->createUrl('order/ordercost');?>&from=wedding_feast&order_id=<?php echo $_GET['order_id']?>&money="+$("#feast_cost_data").html();
        });
        $("#wedding_cost").on("click",function(){
            location.href="<?php echo $this->createUrl('order/ordercost');?>&from=wedding&order_id=<?php echo $_GET['order_id']?>&money="+$("#wedding_cost_data").html();
        });

    });

</script>
</body>
</html>
