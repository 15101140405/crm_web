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
    <script type="text/javascript" src="js/input.js"></script>
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
        <div class="video_desc clearfix" style="margin-bottom:30px;margin-top:80px">
            <ul class="left desc_box">
        <?php if($_GET['type'] == ""){?>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">案例名称</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <input class="input_in" id="case_name" type="text" value="" placeholder="请输入案例名称">
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
        <?php }else if($_GET['type'] == "theme"){?>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">案例名称</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <input class="input_in" id="case_name" type="text" value="<?php echo $case['CI_Name']?>" placeholder="请输入案例名称">
                    </div>
                    <span class="tip tip2 hid" id="name_t">请输入案例名称</span>
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
        <?php }?>
                <!-- <li class="desc_item">
                    <div class="tit_box left">
                        <label for="">描述</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <textarea class="input_in" name="" id="" cols="30" rows="10">请输入视频描述</textarea>
                    </div>
                </li> -->
                <!-- <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">选择类别：</label>
                    </div>
                    <div class="input_box left">
                        <input class="input_in" type="text" value="请输入标题">
                    </div>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">选择供应商：</label>
                    </div>
                    <div class="input_box left">
                        <input class="input_in" type="text" value="请输入标题">
                    </div>
                </li> -->
            </ul>
            <div class="right video_cover">
                <div class="cover_box">
        <?php if(isset($_GET['type'])){
                if($_GET['type'] == 'theme'){?>
                    <img src="<?php echo $case['CI_Pic']?>" style="width:120px;" alt="" id="poster_img">
        <?php }else{ ?>
                    <img src="images/cover.jpg" alt="" id="poster_img">
        <?php }}else{?>
                    <img src="images/cover.jpg" alt="" id="poster_img">
        <?php }?>
                </div>
                <button id="uploadsingle">上传视频封面</button>
                <span class="tip tip2 hid" id="poster_t">请上传示意图</span>
            </div>
        </div>
<!--
        <ul class="regist_ulist">
            <li>
                <label>手机：</label>
                <input class="inputItem" type="text" placeholder="默认" />
            </li>
            <li>
                <label>密码：</label>
                <input class="inputItem" type="password" placeholder="默认" />
            </li>
            <li>
                <label>短信验证码：</label>
                <input class="left inputItem short" type="text" placeholder="默认" />
                <span class="get_code">获取短信验证码</span>
            </li>
            <li class="agree_txt clearfix">
                <input class="left" type="checkbox">
                <p class="left">同意<span>《xx注册协议》</span>
                </p>
            </li>
            <li>
                <button class="registbtn">立即注册</button>
            </li>
        </ul>
-->
        <!--上传进度条-->
        <ul class="upload_pro_list" id="upload_pro_list">
            <!-- <li>
                <div class="upload_name">当前上传: <span>dddkkk</span>
                </div>
                <div class="progress_box clearfix">
                    <p class="info">上传中</p>
                    <div class="progress_bar left">
                        <div class="progress" style="width:50%">
                            <span class="num">50% <i></i></span>
                        </div>
                    </div>
                    <span class="left calcel">取消本次上传</span>
                    <div class="upload_state clearfix">
                        <p class="left">当前上传速度：783.99kb/s</p>
                        <p class="left">当前上传速度：783.99kb/s</p>
                        <p class="left">当前上传速度：783.99kb/s</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="upload_name">当前上传: <span>dddkkk</span>
                </div>
                <div class="progress_box clearfix">
                    <p class="info">上传中</p>
                    <div class="progress_bar left">
                        <div class="progress" style="width:50%">
                            <span class="num">50% <i></i></span>
                        </div>
                    </div>
                    <span class="left calcel">取消本次上传</span>
                    <div class="upload_state clearfix">
                        <p class="left">当前上传速度：783.99kb/s</p>
                        <p class="left">当前上传速度：783.99kb/s</p>
                        <p class="left">当前上传速度：783.99kb/s</p>
                    </div>
                </div>
            </li>
            <li>
                <div class="upload_name">当前上传: <span>dddkkk</span>
                </div>
                <div class="progress_box clearfix">
                    <p class="info">上传中</p>
                    <div class="progress_bar left">
                        <div class="progress" style="width:50%">
                            <span class="num">50% <i></i></span>
                        </div>
                    </div>
                    <span class="left calcel">取消本次上传</span>
                    <div class="upload_state clearfix">
                        <p class="left">当前上传速度：783.99kb/s</p>
                        <p class="left">当前上传速度：783.99kb/s</p>
                        <p class="left">当前上传速度：783.99kb/s</p>
                    </div>
                </div>
            </li> -->
        </ul>
        <div class="index_con_box" style="margin-bottom:20px;">
            <div class="con">
                <ul class="upload_list" id="resources_list">
            <?php foreach ($resources as $key => $value) {?>
                    <li class="clearfix" tap="" cr-sort="1" cr-id="754">
                        <div class="upload_con_box left clearfix">
                            <div class="video_img left">
                                <img src="<?php echo $value['CR_Path']?>" alt="">
                            </div>
                            <div class="video_info left">
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix" style="width:40%">
                            <a class="edit_btn left del_resource" href="javascript:;">删除</a>
                        </div>
                    </li>
            <?php }?>
                </ul>
            </div>
        </div>
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
<script>
    $(function(){
        //初始渲染
        $.cookie('img',"<?php echo $pic ?>");
        $("#CI_Show").val(<?php if(isset($case['CI_Show'])){echo $case['CI_Show'];}?>);

        //保存
        $("#save").on("click",function(){
            var data;
            data = {
                CI_Name: $("#case_name").val(),
                CI_Show: $("#CI_Show option:selected").val(),
                CI_Pic: $.cookie('img'),
                case_resource: $.cookie('imgs'),
                account_id: $.cookie('account_id'),
                product_list: "<?php echo $_GET['product_list']?>",
                total_price: "<?php echo $_GET['product_list']?>",
                final_price: "<?php echo $_GET['final_price']?>",
                feast_discount: "",
                other_discount: "",
                staff_hotel_id: $("#hotel option:selected").attr("staff-hotel-id"),
            }
            $(".tip").removeClass("hid");
            $(".tip").addClass("hid");
            if(data.CI_Name == ""){$("#name_t").removeClass("hid")};
            if(data.CI_Pic == "null" || data.CI_Pic == null){$("#poster_t").removeClass("hid")};
            if(data.case_resource == "null" || data.case_resource == null){$("#resources_t").removeClass("hid")};
            if($("#case_name").val() == "" || $.cookie("img") == null || $.cookie("img") == "null" || $.cookie("imgs") == null || $.cookie("imgs") == "null"){
                alert("请补全信息");
            }else{
            <?php if($_GET['type'] != ""){?>
                <?php if($_GET['type'] == 'theme' && isset($_GET['ci_id'])){?>
                var url = "<?php echo $this->createUrl("background/theme_edit");?>";
                <?php }else{?>
                var url = "<?php echo $this->createUrl("background/set_upload");?>";
                <?php }?>
            <?php }else{?>
                var url = "<?php echo $this->createUrl("background/set_upload");?>";
            <?php }?>
                console.log(data);
                $.post(url,data,function(){
                    $.cookie('img',null); 
                    $.cookie('imgs',null); 
                <?php if($_GET['type']==""){?>
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=5";
                <?php }else if($_GET['type'] == 'meeting_set'){?>
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=5";
                <?php }else if($_GET['type'] == 'theme'){?>
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=4";
                <?php }?> 
                });
            };  
        });
    })
</script>
</body>

</html>