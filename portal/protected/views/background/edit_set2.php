<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>上传</title>
    <link rel="stylesheet" type="text/css" href="css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <script type="text/javascript" src="http://file.cike360.com/swfupload/swfupload.js"></script>
    <script type="text/javascript" src="js/handlers.js"></script>
    <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="js/input.js"></script>
    <script type="text/javascript" src="js/select.js"></script>
    <script type="text/javascript">
        var swfu;
        var swfu2;
        var uploadsite = "http://file.cike360.com";
        window.onload = function () {
            $.cookie("imgs", "");
            var settings = {
                flash_url: uploadsite+"/swfupload/swfupload.swf",
                upload_url: uploadsite + "/upload.ashx",
                post_params: {},
                file_size_limit: "4100MB",
                file_types: "*.jpg;*.gif;*.png;*.bmp;*.mp4;",
                file_types_description: "资源文件",
                file_upload_limit: 100,
                file_queue_limit: 500,
                custom_settings: {
                    progressTarget: "fsUploadProgress",
                    cancelButtonId: "btnCancel"
                },
                debug: false,
                //button_action: SWFUpload.BUTTON_ACTION.SELECT_FILE,
                button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,
                // Button Settings
                button_placeholder_id: "btnupload",
                button_image_url:"images/btn.png",
                button_width: 120,
                button_height: 35,
                button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
                button_cursor: SWFUpload.CURSOR.HAND,
            
                swfupload_preload_handler: preLoad,
                swfupload_load_failed_handler: loadFailed,
                file_queued_handler: fileQueued,
                file_queue_error_handler: fileQueueError,
                file_dialog_complete_handler: fileDialogComplete,
                upload_progress_handler: uploadProgress,
                upload_error_handler: uploadError,
                upload_success_handler: uploadSuccess,
                upload_complete_handler: uploadComplete,
                file_dialog_start_handler: fileDialogStart,
                queue_complete_handler: queueComplete, // Queue plugin event

            };
            var settings2= {
                flash_url: uploadsite + "/swfupload/swfupload.swf",
                upload_url: uploadsite + "/upload.ashx",
                post_params: {},
                file_size_limit: "4100MB",
                file_types: "*.jpg;*.gif;*.png;*.bmp;",
                file_types_description: "图片文件",
                file_upload_limit: 100,
                file_queue_limit: 100,
                custom_settings: {
                    progressTarget: "fsUploadProgress",
                    cancelButtonId: "btnCancel"
                },
                debug: false,
                button_action: SWFUpload.BUTTON_ACTION.SELECT_FILE,
                //button_action: SWFUpload.BUTTON_ACTION.SELECT_FILES,
                // Button Settings
                button_placeholder_id: "uploadsingle",
                button_image_url: "images/btn.png",
                button_width: 120,
                button_height: 35,
                button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
                button_cursor: SWFUpload.CURSOR.HAND, 
                swfupload_preload_handler: preLoad,
                swfupload_load_failed_handler: loadFailed,
                file_queued_handler: fileQueued,
                file_queue_error_handler: fileQueueError,
                file_dialog_complete_handler: fileDialogComplete,
                upload_progress_handler: uploadProgress,
                upload_error_handler: uploadError,
                upload_success_handler: uploadSuccess2,
                upload_complete_handler: uploadComplete,
                file_dialog_start_handler: fileDialogStart,
            };
            swfu = new SWFUpload(settings);
            swfu2 = new SWFUpload(settings2);
        }
        function queueComplete() {
            //location.href="";
        }

    </script>
</head>

<body style="background:#fff;">
    <!--头部-->
    <div class="upload_top">
        <div class="upload_wapper clearfix">
            <h1 class="logo left"><img src="images/logo.jpg" alt=""></h1>
            <span class="nick right">best</span>
        </div>
    </div>
    <!--视频描述-->
    <div class="upload_wapper">
        <div class="video_desc clearfix" style="margin-bottom:30px;margin-top:80px;">
            <ul class="left desc_box">
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">案例名称</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <input class="input_in" id="case_name" type="text" value="<?php echo $case['CI_Name']?>" placeholder="请输入案例名称">
                    </div>
                    <span class="tip tip2 hid" id="name_t">请上传示意图</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">显示／隐藏</label>
                    </div>
                    <div class="select_c left">
                        <select name="" id="CI_Show">
                            <!-- <option value="">请选择</option> -->
                            <option value="1">显示</option>
                            <option value="0">隐藏</option>
                        </select>
                    </div>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">选择门店</label>
                    </div>
                    <div class="select_c left">
                        <select name="" id="hotel">
                            <!-- <option value="">请选择</option> -->
                    <?php foreach ($hotel as $key => $value) {?>
                            <option value="<?php echo $value['name']?>" staff-hotel-id="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                    <?php }?>
                        </select>
                    </div>
                </li>
            </ul>
            <div class="right video_cover">
                <div class="cover_box">
                    <img src="<?php echo $pic?>" alt="" id="poster_img" style="width: 120px;">
                </div>
                <button id="uploadsingle">上传视频封面</button>
                <span class="tip tip2 hid" id="poster_t">请上传示意图</span>
            </div>
        </div>
        <!-- 已上传资源 -->
        <div class="index_con_box" style="margin-bottom:20px;">
            <div class="con">
                <ul class="upload_list" id="resources_list">
            <?php foreach ($resources as $key => $value) {?>
                    <li class="clearfix" tap='' CR-Sort="<?php echo $value['CR_Sort']?>" CR-ID="<?php echo $value['CR_ID']?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['CR_Path']?>" alt="">
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                        <?php foreach ($value['product'] as $key1 => $value1) {?>
                                <div class="state_box clearfix" product-id="<?php echo $value1['bind_id']?>">
                                    <img class="left" src="images/up06.jpg" alt="">
                                    <span class="left"><?php echo $value1['name']?></span>
                                    <span class="from left"><?php echo $value1['unit_price']?>元／<?php echo $value1['unit']?></span>
                                    <img class="right del_product" src="images/close.png" alt="" style="margin-left:30px;width:10px;height:10px;margin-top:2px;border: 1px solid black;" >
                                </div>

                        <?php }?>
                                <!-- cover_box -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix" style="width:40%">
                            <!-- <span class="left state">转码中</span> -->
                            <!-- <a class="edit_btn left add_class bind" href="javascript:;">绑定产品</a> -->
                            <a class="edit_btn left del_resource" href="javascript:;">删除</a>
                        </div>
                    </li>
            <?php }?>
                </ul>
            </div>
        </div>
        <!--上传进度条-->
        <ul class="upload_pro_list" id="upload_pro_list">
        </ul>
        <span class="tip tip2 hid" id="resources_t">请上传示意图</span>
        <div class="upload_btn_box" style="margin-bottom: 150px;">
            <a href="javascript:;" class="btn active" id="btnupload">添加资源</button>
            <a href="javascript:;" class="btn" id="save">保存</button>
        </div>
    </div>
    <div class="footer">
        <ul class="footer_link_list clearfix">
            <li><a href="javascript:;">关于我们</a>
            </li>
            <li>|</li>
            <li><a class="active" href="javascript:;">关于我们</a>
            </li>
            <li>|</li>
            <li><a href="javascript:;">关于我们</a>
            </li>
            <li>|</li>
            <li><a href="javascript:;">关于我们</a>
            </li>
            </ul>
            <p>京公网安备11010502022785号 京公网安备11010502022785号</p>
            <p>京公网安备11010502022785号</p>
        </div>
    <!--弹层1-->
    <div class="msgbox msgbox_class">
        <div class="msgbox_c" style="width:860px;left:15%;top:10%;height:520px; overflow:scroll">
            <div class="tit_box clearfix">
                <h2 class="left">绑定产品</h2>
                <img class="right close" src="images/close.jpg" alt="">
            </div>
            <div class="index_con_box">
                <div class="con" style="padding-top:0px;">
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
                        </div>
                    </div>
                <ul class="upload_list" id="product_item">
            <?php foreach ($case_data as $key => $value) { ?>
                    <li class="clearfix" tap='<?php echo $value['decoration_tap']?>' product-id ="<?php echo $value['id'];?>">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['ref_pic_url']?>" alt="">
                                <!-- <span>私密视频</span> -->
                            </div>
                            <div class="video_info left">
                                <h3><?php echo $value['name']?></h3>
                                <div class="state_box clearfix">
                                    <img class="left" src="images/up06.jpg" alt="">
                                    <span class="left"><?php echo $value['description']?></span>
                                    <!-- <span class="from left">来自：爱奇艺网页</span> -->
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix">
                            <span class="left state">¥<?php echo $value['unit_price']?>元／<?php echo $value['unit']?></span>
                            <a class="edit_btn left sure_bind" href="javascript:;">确定</a>
                        </div>
                    </li>
            <?php }?>
                </ul>
            </div>
        </div>
    </div>
<script>
    $(function(){
        //初始渲染
        $.cookie('img',"<?php echo $case['CI_Pic']?>");
        $("#CI_Show").val(<?php echo $case['CI_Show']?>);
        $("#hotel").val($("#hotel [staff-hotel-id='<?php echo $Wedding_set['staff_hotel_id']?>']").val());
        //保存
        $("#save").on("click",function(){
            var data = {
                CI_ID : <?php echo $_GET['ci_id']?>,
                CT_ID : <?php echo $_GET['ct_id']?>,
                CI_Name : $("#case_name").val(),
                CI_Show : $("#CI_Show option:selected").val(),
                staff_hotel_id : $("#hotel option:selected").attr("staff-hotel-id"),
                CI_Pic : $.cookie('img'),
                case_resource : $.cookie('imgs'),
                account_id : $.cookie('account_id'),
                CR_Sort : $("#resources_list li:last-child").attr("CR-Sort"),
                product_list : "<?php echo $_GET['product_list']?>",
                final_price : <?php echo $_GET['final_price']?>,
                feast_discount : <?php echo $_GET['feast_discount']?>,
            };
            if($("#resources_list").find("li").length == 0){data.CR_Sort = 0;};
            console.log($("#resources_list").find("li").length);
            console.log(data);
            $(".tip").removeClass("hid");
            $(".tip").addClass("hid");
            if(data.CI_Name == ""){$("#name_t").removeClass("hid")};
            if(data.CI_Pic == "null" || data.CI_Pic == null){$("#poster_t").removeClass("hid")};
            if(data.case_resource == "null" || data.case_resource == null){$("#resources_t").removeClass("hid")};
            if($("#case_name").val() == "" || $.cookie("img") == null || $.cookie("img") == "null" || $.cookie("imgs") == null || $.cookie("imgs") == "null"){
                alert("请补全信息");
            }else{
                $.post("<?php echo $this->createUrl("background/set_edit");?>",data,function(){
                    $.cookie('img',null); 
                    $.cookie('imgs',null); 
                <?php if(!isset($_GET['type'])){?>
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=5";
                <?php }else if($_GET['type'] == 'menu'){?>
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=9";
                <?php }?>
                });
            };  
        });
        //删除资源
        $(".del_resource").on("click",function(){
            $.post("<?php echo $this->createUrl("background/del_resource");?>",{CR_ID : $(this).parent().parent().attr("cr-id")},function(){
                location.reload();
            })
        })
    })
</script>
</body>
</html>