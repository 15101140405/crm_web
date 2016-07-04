<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>官网首页</title>
    <link rel="stylesheet" type="text/css" href="css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
</head>

<body style="background:#6c5256;">
    <div class="wapper index_container">
        <!--header start-->
        <div class="header">
            <h1 class="title">YOU ARE BEST</h1>
            <span class="sub_title">Where there's a will, there's a way.</span>
        </div>
        <!--header end-->
        <!--nav start-->
        <div class="nav_box">
            <ul class="nav_list clearfix" style="margin-right:20px;">
                <li><a href="<?php echo $this->createUrl("background/login");?>">退出</li>
            </ul>
            <ul class="nav_list clearfix" style="margin-right:20px;">
                <li id="host"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=6" >我的主持</a></li>
                <li id="video"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=13" >我的摄像</a></li>
                <li id="camera"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=14" >我的摄影</a></li>
                <li id="makeup"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=15" >我的化妆</a></li>
                <li id="decoration"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=17" >我的场布</a></li>
                <li id="lighting"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=18" >我的灯光</a></li>
                <li id="sound"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=19" >我的音响</a></li>
                <li id="shipin"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=20" >我的视频</a></li>
            </ul>
            <ul class="nav_list clearfix" style="float:left;margin-left:20px;">
                <li id="introduction"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=16" >门店介绍</a>
                </li>
                <li id="feast"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=9" >餐饮</a>
                </li>
                <li id="set"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=5" >套系</a>
                </li>
                <li id="decration"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=7" >场地布置</a>
                </li>
                <li id="service"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=61" >服务人员</a>
                </li>
                <li id="lss"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=8" >灯光／音响／视频</a>
                </li>
                <li id="case"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=2" >案例</a>
                </li>
                <li id="theme"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=4">主题婚礼</a>
                </li>
                <li id="classic"><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=1">经典婚礼</a>
                </li>
                <!-- <li><a href="javascript:;">ABOUT US</a>
                </li> -->
            </ul>
        </div>
        <!--nav end-->
        <!--con start-->
        <div class="index_con_box">
            <div class="con">
                <div class="top clearfix">
                    <div class="left clearfix">
                        <div class="select_box left" id="shaixuan1">
                            <div class="my_select clearfix">
                                <span class="select_con">请选择</span>
                                <span class="down"></span>
                            </div>
                            <select class="select_list" name="" id="select_type">
                                <option value="请选择" type-id="0">请选择</option>
                            <?php if($_GET['CI_Type'] == 7){foreach ($tap as $key => $value) {?>
                                <option value="<?php echo $value['name']?>" type-id="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                            <?php }}else if($_GET['CI_Type'] == 8){?>
                                <option value="灯光" type-id="8">灯光</option>
                                <option value="音响" type-id="23">音响</option>
                                <option value="视频" type-id="9">视频</option>
                            <?php }else if($_GET['CI_Type'] == 61){?>
                                <option value="主持" type-id="3">主持</option>
                                <option value="摄像" type-id="4">摄像</option>
                                <option value="摄影" type-id="5">摄影</option>
                                <option value="化妆" type-id="6">化妆</option>
                                <option value="其他" type-id="7">其他</option>
                            <?php }?>
                            </select>
                        </div>
                        <!-- <p class="left" id="shaixuan_remark">共<span class="num">1</span>个视频</p> -->
                    </div>
                    <button class="right upload_new_btn" id="upload">上传新视频</button>
                    <button class="right upload_new_btn" id="upload_dish">上传新菜品</button>
                    <button class="right upload_new_btn" id="upload_meeting_menu" style="float:left;margin-left:37px;margin-right:37px;;border: 1px solid #6B9913;background: #fff;color: #6B9913;border-radius: 0;">＋会议餐</button>
                    <button class="right upload_new_btn" id="upload_wedding_menu" style="float:left;float: left;border: 1px solid #6B9913;background: #fff;color: #6B9913;border-radius: 0;">＋婚宴</button>
                </div>
                <ul class="upload_list" id="product_item">
            <?php if($_GET['CI_Type'] == 2 || $_GET['CI_Type'] == 16 || $_GET['CI_Type'] == 1 || $_GET['CI_Type'] == 4){
                    foreach ($case_data as $key => $value) {
                    if($value['CI_Type'] == $_GET['CI_Type']){?>
                    <li class="clearfix" tap='' CI-ID="<?php echo $value['CI_ID']?>" CT-ID="<?php echo $value['CT_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['CI_Pic']?>" alt="">
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                                <h3><?php echo $value['CI_Name']?></h3>
                                <div class="state_box clearfix">
                                    <!-- <img class="left" src="images/up06.jpg" alt=""> -->
                                    <span class="left"><?php echo $value['CI_Remarks']?></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <!-- <span class="left state">转码中</span> -->
                            <a class="edit_btn left del" style="background-color: #f82d00;" href="javascript:;">删除</a>
                            <a class="edit_btn left edit" href="javascript:;">编辑</a>
                        </div>
                    </li>
            <?php }}}else if($_GET['CI_Type'] == 5){
                        foreach ($case_data as $key => $value){
                            if($value['CI_Type'] == 5 || $value['CI_Type'] == 12){?>
                    <li class="clearfix" tap='' CI-ID="<?php echo $value['CI_ID']?>" CT-ID="<?php echo $value['CT_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['CI_Pic']?>" alt="">
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                                <h3><?php echo $value['CI_Name']?></h3>
                                <div class="state_box clearfix">
                                    <!-- <img class="left" src="images/up06.jpg" alt=""> -->
                                    <span class="left"><?php echo $value['CI_Remarks']?></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <!-- <span class="left state">转码中</span> -->
                            <a class="edit_btn left del" style="background-color: #f82d00;" href="javascript:;">删除</a>
                            <a class="edit_btn left edit" href="javascript:;">编辑</a>
                        </div>
                    </li>
            <?php }}}else if($_GET['CI_Type']== 7){
                        foreach ($case_data as $key => $value) {?>
                    <li class="clearfix" tap='<?php echo $value['decoration_tap']?>' product-id ="<?php echo $value['id'];?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['ref_pic_url']?>" alt="">
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                                <h3><?php echo $value['name']?></h3>
                                <div class="state_box clearfix">
                                    <!-- <img class="left" src="images/up06.jpg" alt=""> -->
                                    <span class="left"><?php echo $value['description']?></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <span class="left state">¥<?php echo $value['unit_price']?>元／<?php echo $value['unit']?></span>
                            <a class="edit_btn left del" style="background-color: #f82d00;" href="javascript:;">删除</a>
                            <a class="edit_btn left edit" href="javascript:;">编辑</a>
                        </div>
                    </li>
            <?php }}else if($_GET['CI_Type']== 6 || $_GET['CI_Type']== 13 || $_GET['CI_Type']== 14 || $_GET['CI_Type']== 15){?>
                    <li class="clearfix" ci-id="<?php echo $case['CI_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="images/self_info.png" alt="">
                            </div>
                            <div class="video_info left">
                                <h3>个人信息</h3>
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <a class="edit_btn left" id="self_info" href="javascript:;">管理个人信息</a>
                        </div>
                    </li>
                    <li class="clearfix" ci-id="<?php echo $case['CI_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="images/host_video.png" alt="">
                            </div>
                            <div class="video_info left">
                                <h3>我的视频</h3>
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <a class="edit_btn left" id="video" href="javascript:;">管理视频</a>
                        </div>
                    </li>
                    <li class="clearfix" ci-id="<?php echo $case['CI_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="images/host_img.jpeg" alt="">
                            </div>
                            <div class="video_info left">
                                <h3>我的图片</h3>
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <a class="edit_btn left" id="img" href="javascript:;">管理图片</a>
                        </div>
                    </li>
                    <li class="clearfix" service-person-id="<?php echo $service_person['id']?>" ci-id="<?php echo $case['CI_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="images/host_price.jpeg" alt="">
                            </div>
                            <div class="video_info left">
                                <h3>我的报价</h3>
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <a class="edit_btn left" id="product" href="javascript:;">管理报价</a>
                        </div>
                    </li>
            <?php }else if($_GET['CI_Type'] == 17 || $_GET['CI_Type'] == 18 || $_GET['CI_Type'] == 19 || $_GET['CI_Type'] == 20){?>
                    <li class="clearfix" ci-id="<?php echo $case['CI_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="images/self_info.png" alt="">
                            </div>
                            <div class="video_info left">
                                <h3>个人信息</h3>
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <a class="edit_btn left" id="self_info" href="javascript:;">管理个人信息</a>
                        </div>
                    </li>
                    <li class="clearfix" service-person-id="<?php echo $service_person['id']?>" ci-id="<?php echo $case['CI_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="images/host_price.jpeg" alt="">
                            </div>
                            <div class="video_info left">
                                <h3 id="my_product">我的产品</h3>
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <a class="edit_btn left" id="product" href="javascript:;">管理产品</a>
                        </div>
                    </li>
            <?php }else if($_GET['CI_Type'] == 8){
                    foreach ($supplier_product as $key => $value) {?>
                    <li class="clearfix" type-id='<?php echo $value['supplier_type_id']?>' product-id ="<?php echo $value['id'];?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['ref_pic_url']?>" alt="">
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                                <h3><?php echo $value['name']?></h3>
                                <div class="state_box clearfix">
                                    <!-- <img class="left" src="images/up06.jpg" alt=""> -->
                                    <span class="left"><?php echo $value['description']?></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <span class="left state">¥<?php echo $value['unit_price']?>元／<?php echo $value['unit']?></span>
                            <a class="edit_btn left del" style="background-color: #f82d00;" href="javascript:;">删除</a>
                            <a class="edit_btn left edit" href="javascript:;">编辑</a>
                        </div>
                    </li>
            <?php }}else if($_GET['CI_Type'] == 9){?>
                <?php foreach ($menu as $key => $value) {?>
                    <li class="clearfix" CI-ID="<?php echo $value['CI_ID']?>" CT-ID="<?php echo $value['CT_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['CI_Pic']?>" alt="">
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                                <h3><?php echo $value['name']?></h3>
                                <div class="state_box clearfix">
                                    <!-- <img class="left" src="images/up06.jpg" alt=""> -->
                                    <span class="left"></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <span class="left state">¥<?php echo $value['final_price']?>元</span>
                            <a class="edit_btn left del_menu"  style="background-color: #f82d00;" href="javascript:;">删除</a>
                            <a class="edit_btn left edit_menu" href="javascript:;">编辑</a>
                        </div>
                    </li>
                <?php }?>
                <?php foreach ($supplier_product as $key => $value) {?>
                    <li class="clearfix" type-id='<?php echo $value['supplier_type_id']?>' product-id ="<?php echo $value['id'];?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['ref_pic_url']?>" alt="">
                                <span>菜品</span>
                            </div>
                            <div class="video_info left">
                                <h3><?php echo $value['name']?></h3>
                                <div class="state_box clearfix">
                                    <!-- <img class="left" src="images/up06.jpg" alt=""> -->
                                    <span class="left"><?php echo $value['description']?></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <span class="left state">¥<?php echo $value['unit_price']?>元／<?php echo $value['unit']?></span>
                            <a class="edit_btn left del_dish" style="background-color: #f82d00;" href="javascript:;">删除</a>
                            <a class="edit_btn left edit" href="javascript:;">编辑</a>
                        </div>
                    </li>
            <?php }}else if($_GET['CI_Type'] == 61){?>
            <?php   foreach ($supplier as $key => $value) {?>
                    <li class="clearfix" tap="<?php echo $value['type_id']?>" supplier-id="<?php echo $value['id']?>" service-person-id="<?php echo $value['service_person_id']?>" ci-id="<?php echo $value['CI_ID']?>" ci-type="<?php echo $value['CI_Type']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <?php if($value['CI_Pic'] == ""){?>
                                <img src="http://file.cike360.com/upload/伊缘圆 (3)20160519121134_sm.jpg" alt="">
                                <?php }else{?>
                                <img src="http://file.cike360.com<?php echo $value['CI_Pic']?>" alt="">
                                <?php }?>
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                                <h3 style="margin-top:1rem;"><?php echo $value['name']?></h3>
                                <div class="state_box clearfix">
                                    <!-- <img class="left" src="images/up06.jpg" alt=""> -->
                                    <span class="left">手机号：<?php echo $value['telephone']?></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <span class="left state"><?php echo $value['type_name']?></span>
                            <a class="edit_btn left del" style="background-color: #f82d00;" href="javascript:;">删除</a>
                            <a class="edit_btn left edit" href="javascript:;">编辑</a>
                            <a class="edit_btn left add_product" href="javascript:;">添加产品</a>
                        </div>
                    </li>
            <?php }}?>
                </ul>
            </div>
        </div>
        <!--con end-->
        <div class="index_foot">
            2016 @ 北京浩瀚一方互联网科技有限责任公司
        </div>
    </div>
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/select.js"></script>
<script>
    $(function(){
        //如果不是策划师，则去掉：案例、套系、场地布置、灯光／音响／视频、婚宴
        var department_list = "<?php if(isset($_COOKIE['department_list'])){echo $_COOKIE['department_list'];}?>";
        department_list = department_list.substring(0,department_list.length-1);
        department_list = department_list.substring(1);
        var list = new Array();
        list = department_list.split(',');
        var t = 0;      /*＊＊＊＊策划师＊＊＊＊*/ 
        var t1 = 0;     //主持人
        var t2 = 0;     //摄像师
        var t3 = 0;     //摄影师
        var t4 = 0;     //化妆师
        var t5 = 0;      /*＊＊＊＊内容管理员＊＊＊＊*/ 
        var t6 = 0;     //场布供应商
        var t7 = 0;     //灯光供应商
        var t8 = 0;     //音响供应商
        var t9 = 0;     //视频供应商

        for (i=0;i<list.length;i++) {
            if(list[i] == 2 || list[i] == 3 || list[i] == 5 || list[i] == 6){t++};
            if(list[i] == 11){t1++};
            if(list[i] == 12){t2++};
            if(list[i] == 13){t3++};
            if(list[i] == 14){t4++};
            if(list[i] == 0){t5++};
            if(list[i] == 15){t6++};
            if(list[i] == 16){t7++};
            if(list[i] == 17){t8++};
            if(list[i] == 18){t9++};
        };
        console.log(list);
        console.log(t);
        console.log(t1);
        console.log(t2);
        console.log(t3);
        console.log(t4);
        console.log(t5);
        console.log(t6);
        console.log(t7);
        console.log(t8);
        console.log(t9);
        if(t==0){ //不是策划师
            $("#case").remove();
            $("#set").remove();
            $("#decration").remove();
            $("#service").remove();
            $("#lss").remove();
            $("#feast").remove();
        };
        if(t1==0){  //不是主持人
            $("#host").remove();
        };
        if(t2==0){  //不是摄像师
            $("#video").remove();
        };
        if(t3==0){  //不是摄影师
            $("#camera").remove();
        };
        if(t4==0){  //不是化妆师
            $("#makeup").remove();
        };  
        if(t5==0){  //不是内容管理员
            $("#theme").remove();
            $("#classic").remove();
        };  
        if(t6==0){  //不是场布供应商
            $("#decoration").remove();
        }; 
        if(t7==0){  //不是灯光供应商
            $("#lighting").remove();
        }; 
        if(t8==0){  //不是音响供应商
            $("#sound").remove();
        }; 
        if(t9==0){  //不是视频供应商
            $("#shipin").remove();
        }; 

        //导航
        if(<?php echo $_GET['CI_Type']?> == 1){$("#classic a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 4){$("#theme a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 2){$("#case a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 16){$("#introduction a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 5){$("#set a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 0){$("#host a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 13){$("#video a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 14){$("#camera a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 15){$("#makeup a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 7){$("#decration a").addClass("active")};
        if(<?php echo $_GET['CI_Type']?> == 61){$("#service a").addClass("active")};
        if(<?php echo $_GET['CI_Type']?> == 8){$("#lss a").addClass("active")};
        if(<?php echo $_GET['CI_Type']?> == 9){$("#feast a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 17){$("#decoration a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 18){$("#lighting a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 19){$("#sound a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 20){$("#shipin a").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};


        //上传按钮－样式渲染
        if(<?php echo $_GET['CI_Type']?> == 1){$("#upload").html("新增经典婚礼");$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};
        if(<?php echo $_GET['CI_Type']?> == 4){$("#upload").html("新增主题婚礼");$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};
        if(<?php echo $_GET['CI_Type']?> == 2){$("#upload").html("新增案例");$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};
        if(<?php echo $_GET['CI_Type']?> == 16){$("#upload").html("新增门店");$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};
        if(<?php echo $_GET['CI_Type']?> == 5){$("#upload").remove();$("#upload_dish").remove();$("#upload_meeting_menu").html("+会议套系");$("#upload_wedding_menu").html("+婚礼套系");};
        if(<?php echo $_GET['CI_Type']?> == 6 || <?php echo $_GET['CI_Type']?> == 13 || <?php echo $_GET['CI_Type']?> == 14 || <?php echo $_GET['CI_Type']?> == 15){$("#upload").remove();$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};
        if(<?php echo $_GET['CI_Type']?> == 7 || <?php echo $_GET['CI_Type']?> == 8){$("#upload").html("新增产品");$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};
        if(<?php echo $_GET['CI_Type']?> == 61){$("#upload").html("新增人员");$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};
        if(<?php echo $_GET['CI_Type']?> != 9){$("#upload_dishes").remove()};
        if(<?php echo $_GET['CI_Type']?> == 9){$("#upload").remove();};
        if(<?php echo $_GET['CI_Type']?> == 17 || <?php echo $_GET['CI_Type']?> == 18 || <?php echo $_GET['CI_Type']?> == 19 || <?php echo $_GET['CI_Type']?> == 20){$("#upload").remove();$("#upload_dish").remove();$("#upload_meeting_menu").remove();$("#upload_wedding_menu").remove();};


        //上传按钮－点击事件
        $("#upload").on("click",function(){
            if(<?php echo $_GET['CI_Type']?> == 1){location.href="<?php echo $this->createUrl("background/upload_case");?>&ci_type=1"};
            if(<?php echo $_GET['CI_Type']?> == 4){location.href="<?php echo $this->createUrl("background/upload_set1");?>&type=theme"};
            if(<?php echo $_GET['CI_Type']?> == 2){location.href="<?php echo $this->createUrl("background/upload_case");?>"};
            if(<?php echo $_GET['CI_Type']?> == 16){location.href="<?php echo $this->createUrl("background/upload_case");?>&ci_type=16"};
            if(<?php echo $_GET['CI_Type']?> == 7){location.href="<?php echo $this->createUrl("background/upload_product");?>"};
            if(<?php echo $_GET['CI_Type']?> == 8){location.href="<?php echo $this->createUrl("background/upload_product_lss");?>"};
            if(<?php echo $_GET['CI_Type']?> == 61){location.href="<?php echo $this->createUrl("background/upload_service_person");?>"};
        });
        $("#upload_dish").on("click",function(){
            location.href="<?php echo $this->createUrl("background/upload_dish");?>";
        });
    <?php if($_GET['CI_Type'] == 9){?>
        $("#upload_wedding_menu").on("click",function(){
            location.href="<?php echo $this->createUrl("background/upload_menu1");?>";
        });
        $("#upload_meeting_menu").on("click",function(){
            location.href="<?php echo $this->createUrl("background/upload_menu1");?>&type=meeting_menu";
        });
    <?php }else if($_GET['CI_Type'] == 5){?>
        $("#upload_wedding_menu").on("click",function(){
            location.href="<?php echo $this->createUrl("background/upload_set1");?>";
        });
        $("#upload_meeting_menu").on("click",function(){
            location.href="<?php echo $this->createUrl("background/upload_set1");?>&type=meeting_set";
        });
    <?php }?>
        //筛选导航
        $('#select_type').change(function(){
            $("#product_item li").removeClass("hid");
            $("#product_item li").addClass("hid");
            var tap = $(this).children('option:selected').attr("type-id");
            <?php if($_GET['CI_Type'] == 7){?>
            if(tap != 0){$("[tap='"+tap+"']").removeClass("hid")}else{$("#product_item li").removeClass("hid");};
            <?php }else if($_GET['CI_Type'] == 8){?>
            if(tap != 0){$("[type-id='"+tap+"']").removeClass("hid")}else{$("#product_item li").removeClass("hid");};
            <?php }else if($_GET['CI_Type']){?>
            if(tap != 0){$("[tap='"+tap+"']").removeClass("hid")}else{$("#product_item li").removeClass("hid");};
            <?php }?>
        });

        //点击编辑
        $(".edit").on("click",function(){
            if("<?php echo $_GET['CI_Type']?>" == 2){
                location.href = "<?php echo $this->createUrl("background/edit_case");?>&ci_id=" + $(this).parent().parent().attr('CI-ID');
            };
            if("<?php echo $_GET['CI_Type']?>" == 4){
                location.href = location.href = "<?php echo $this->createUrl("background/edit_set1");?>&type=theme&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&ct_id="  + $(this).parent().parent().attr('CT-ID');
            };
            if("<?php echo $_GET['CI_Type']?>" == 16){
                location.href = "<?php echo $this->createUrl("background/edit_case");?>&ci_id=" + $(this).parent().parent().attr('CI-ID');
            };
            if("<?php echo $_GET['CI_Type']?>" == 5){
                location.href = "<?php echo $this->createUrl("background/upload_set1");?>&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&ct_id="  + $(this).parent().parent().attr('CT-ID');
            };
            if('<?php echo $_GET['CI_Type']?>' == 7){
                location.href = "<?php echo $this->createUrl("background/edit_supplier_product");?>&product_id=" + $(this).parent().parent().attr('product-id');
            };
            if('<?php echo $_GET['CI_Type']?>' == 8){
                location.href = "<?php echo $this->createUrl("background/edit_supplier_product");?>&type=lss&product_id=" + $(this).parent().parent().attr('product-id');
            };
            if('<?php echo $_GET['CI_Type']?>' == 9){
                location.href = "<?php echo $this->createUrl("background/edit_supplier_product");?>&type=dish&product_id=" + $(this).parent().parent().attr('product-id');
            };
            if('<?php echo $_GET['CI_Type']?>' == 61){
                location.href="<?php echo $this->createUrl("background/upload_service_person");?>&supplier_id="+$(this).parent().parent().attr('supplier-id')+"&service_person_id="+$(this).parent().parent().attr('service-person-id')+"&ci_id="+$(this).parent().parent().attr('ci-id')+"&ci_type="+$(this).parent().parent().attr('ci-type');
            }
        });
        $(".edit_menu").on("click",function(){
            location.href = location.href = "<?php echo $this->createUrl("background/edit_set1");?>&type=menu&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&ct_id="  + $(this).parent().parent().attr('CT-ID');
        })

        //四大金刚，编辑个人信息
        $("#self_info").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_host_self_info");?>&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&CI_Type=<?php echo $_GET['CI_Type']?>";
        });
        $("#video").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_host_video");?>&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&CI_Type=<?php echo $_GET['CI_Type']?>";
        });
        $("#img").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_host_img");?>&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&CI_Type=<?php echo $_GET['CI_Type']?>";
        });
        $("#product").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_product");?>&service_person_id=" + $(this).parent().parent().attr('service-person-id') + "&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&CI_Type=<?php echo $_GET['CI_Type']?>";
        });

        //策划师上传四大金刚产品
        $(".add_product").on("click",function(){
            var service_person_id = $(this).parent().parent().attr('service-person-id');
            var ci_id = $(this).parent().parent().attr('ci-id');
            var ci_type = $(this).parent().parent().attr('ci-type');
            location.href = "<?php echo $this->createUrl("background/edit_product");?>&type=designer_add&service_person_id="+service_person_id+"&ci_id="+ci_id+"&CI_Type="+ci_type;
        });

        //删除
        $(".del").on("click",function(){
            if('<?php echo $_GET['CI_Type']?>' == 1 || '<?php echo $_GET['CI_Type']?>' == 2 || '<?php echo $_GET['CI_Type']?>' == 4 || '<?php echo $_GET['CI_Type']?>' == 5 || '<?php echo $_GET['CI_Type']?>' == 16){
                var data = {
                    CI_ID : $(this).parent().parent().attr("CI-ID"),
                    CI_Type : "<?php echo $_GET['CI_Type']?>",
                };
                console.log(data);
                $.post("<?php echo $this->createUrl("background/del_case");?>",data,function(){
                    location.reload();
                });
            }else if('<?php echo $_GET['CI_Type']?>' == 7 || '<?php echo $_GET['CI_Type']?>' == 8){
                var data = {
                    product_id : $(this).parent().parent().attr("product-id")
                };
                console.log(data);
                $.post("<?php echo $this->createUrl("background/del_product");?>",data,function(){
                    location.reload();
                });
            }else if('<?php echo $_GET['CI_Type']?>' == 61){
                var data = {
                    supplier_id : $(this).parent().parent().attr("supplier-id")
                };
                console.log(data);
                $.post("<?php echo $this->createUrl("background/del_supplier");?>",data,function(){
                    location.reload();
                });
            }
        });
        $(".del_menu").on("click",function(){
            var data = {
                CI_ID : $(this).parent().parent().attr("CI-ID"),
                CI_Type : "<?php echo $_GET['CI_Type']?>",
            };
            console.log(data);
            $.post("<?php echo $this->createUrl("background/del_case");?>",data,function(){
                location.reload();
            });
        });
        $(".del_dish").on("click",function(){
            var data = {
                product_id : $(this).parent().parent().attr("product-id")
            };
            console.log(data);
            $.post("<?php echo $this->createUrl("background/del_product");?>",data,function(){
                location.reload();
            });
        });
    })
</script>
</body>

</html>