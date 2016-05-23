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
                <li><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=6" id="host">我的主持</li>
            </ul>
            <ul class="nav_list clearfix" style="float:left;margin-left:20px;">
                <li><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=2" id="case">案例</a>
                </li>
                <li><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=5" id="set">套系</a>
                </li>
                <li><a href="<?php echo $this->createUrl("background/index");?>&CI_Type=7" id="decration">场地布置</a>
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
                            <?php foreach ($tap as $key => $value) {?>
                                <option value="<?php echo $value['name']?>" type-id="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                            <?php }?>
                            </select>
                        </div>
                        <!-- <p class="left" id="shaixuan_remark">共<span class="num">1</span>个视频</p> -->
                    </div>
                    <button class="right upload_new_btn" id="upload">上传新视频</button>
                </div>
                <ul class="upload_list" id="product_item">
            <?php if($_GET['CI_Type'] == 2 || $_GET['CI_Type'] == 5){
                    foreach ($case_data as $key => $value) {
                    if($value['CI_Type'] == $_GET['CI_Type']){?>
                    <li class="clearfix" tap='' CI-ID="<?php echo $value['CI_ID']?>" CT-ID="<?php echo $value['CT_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['CI_Pic']?>" alt="">
                                <span>私密视频</span>
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
                            <a class="edit_btn left" href="javascript:;">编辑</a>
                        </div>
                    </li>
            <?php }}}else if($_GET['CI_Type']== 7){
                        foreach ($case_data as $key => $value) {?>
                    <li class="clearfix" tap='<?php echo $value['decoration_tap']?>' product-id ="<?php echo $value['id'];?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['ref_pic_url']?>" alt="">
                                <span>私密视频</span>
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
                            <a class="edit_btn left" href="javascript:;">编辑</a>
                        </div>
                    </li>
            <?php }}else if($_GET['CI_Type']== 6){?>
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
            <?php }?>
                </ul>
            </div>
        </div>
        <!--con end-->
        <div class="index_foot">
            FOOT AREA
        </div>
    </div>
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/select.js"></script>
<script>
    $(function(){
        //如果不是策划师，则去掉：案例、套系、产品
        var department_list = "<?php if(isset($_COOKIE['department_list'])){echo $_COOKIE['department_list'];}?>";
        department_list = department_list.substring(0,department_list.length-1);
        department_list = department_list.substring(1);
        var list = new Array();
        list = department_list.split(',');
        var t = 0;
        var t1 = 0;
        for (i=0;i<list.length;i++) {
            if(list[i] == 2 || list[i] == 3 || list[i] == 5 || list[i] == 6){t++};
            if(list[i] == 11){t1++};
        };
        console.log(list);
        console.log(t);
        if(t==0){
            $("#case").remove();
            $("#set").remove();
            $("#decration").remove();
        }else if(t1==0){
            $("#host").remove();
        }  

        //下拉筛选
        if(<?php echo $_GET['CI_Type']?> == 2){$("#case").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 5){$("#set").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 6){$("#host").addClass("active");$("#shaixuan1").remove();$("#shaixuan_remark").remove();};
        if(<?php echo $_GET['CI_Type']?> == 7){$("#decration").addClass("active")};

        //上传按钮
        if(<?php echo $_GET['CI_Type']?> == 2){$("#upload").html("新增案例")};
        if(<?php echo $_GET['CI_Type']?> == 5){$("#upload").html("新增套系")};
        if(<?php echo $_GET['CI_Type']?> == 6){$("#upload").remove()};
        if(<?php echo $_GET['CI_Type']?> == 7){$("#upload").html("新增产品")};

        $("#upload").on("click",function(){
            if(<?php echo $_GET['CI_Type']?> == 2){location.href="<?php echo $this->createUrl("background/upload_case");?>"};
            if(<?php echo $_GET['CI_Type']?> == 5){location.href="<?php echo $this->createUrl("background/upload_set1");?>"};
            if(<?php echo $_GET['CI_Type']?> == 7){location.href="<?php echo $this->createUrl("background/upload_product");?>"};
        });

        $('#select_type').change(function(){
            $("#product_item li").removeClass("hid");
            $("#product_item li").addClass("hid");
            var tap = $(this).children('option:selected').attr("type-id");
            if(tap != 0){$("[tap='"+tap+"']").removeClass("hid")}else{$("#product_item li").removeClass("hid");};
        });

        //点击编辑
        $(".edit_btn").on("click",function(){
            if("<?php echo $_GET['CI_Type']?>" == 2){
                location.href = "<?php echo $this->createUrl("background/edit_case");?>&ci_id=" + $(this).parent().parent().attr('CI-ID');
            };
            if("<?php echo $_GET['CI_Type']?>" == 5){
                location.href = "<?php echo $this->createUrl("background/edit_set1");?>&ci_id=" + $(this).parent().parent().attr('CI-ID') + "&ct_id="  + $(this).parent().parent().attr('CT-ID');
            };
            if('<?php echo $_GET['CI_Type']?>' == 7){
                location.href = "<?php echo $this->createUrl("background/edit_supplier_product");?>&product_id=" + $(this).parent().parent().attr('product-id');
            };
        });

        //我的视频
        $("#self_info").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_host_self_info");?>&ci_id=" + $(this).parent().parent().attr('CI-ID');
        });
        $("#video").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_host_video");?>&ci_id=" + $(this).parent().parent().attr('CI-ID');
        });
        $("#img").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_host_img");?>&ci_id=" + $(this).parent().parent().attr('CI-ID');
        });
        $("#product").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_product");?>&service_person_id=" + $(this).parent().parent().attr('service-person-id') + "&ci_id=" + $(this).parent().parent().attr('CI-ID');
        });
    })
</script>
</body>

</html>