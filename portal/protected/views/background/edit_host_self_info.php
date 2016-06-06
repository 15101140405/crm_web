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
            /*swfu = new SWFUpload(settings);*/
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
                        <label for="">头像</label>
                        <span class="must">*</span>
                    </div>
                    <div class="right video_cover">
                        <div class="cover_box">
                            <img src="<?php echo $pic?>" alt="" id="poster_img" style="width: 120px;">
                        </div>
                        <button id="uploadsingle">上传视频封面</button>
                        <span class="tip tip2 hid" id="poster_t">请上传示意图</span>
                    </div>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">姓名</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <input class="input_in" id="case_name" type="text" value="<?php echo $case['CI_Name']?>" placeholder="请输入您的姓名">
                    </div>
                    <!-- <span class="tip tip2 hid" id="name_t">请上传示意图</span> -->
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">手机号</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <input class="input_in" id="phone" type="text" value="<?php echo $staff['telephone']?>" placeholder="请输入您的姓名">
                    </div>
                    <!-- <span class="tip tip2 hid" id="name_t">请上传示意图</span> -->
                </li>
            </ul>
            
        </div>
        <span class="tip tip2 hid" id="resources_t">请上传示意图</span>
        <div class="upload_btn_box" style="margin-bottom: 150px;">
            <!-- <a href="javascript:;" class="btn active hid" id="btnupload">添加资源</button> -->
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
        $.cookie('img',"<?php echo $case['CI_Pic']?>");
        $("#CI_Show").val(<?php echo $case['CI_Show']?>);
        //保存
        $("#save").on("click",function(){
            var data = {
                CI_ID : <?php echo $_GET['ci_id']?>,
                CI_Pic : $.cookie('img'),
                account_id : $.cookie('account_id'),
                CI_Name : $("#case_name").val(),
                phone : $("#phone").val(),
            };
            console.log(data);
            /*$(".tip").removeClass("hid");
            $(".tip").addClass("hid");
            if(data.CI_Name == ""){$("#name_t").removeClass("hid")};
            if(data.CI_Pic == "null" || data.CI_Pic == null){$("#poster_t").removeClass("hid")};
            if(data.case_resource == "null" || data.case_resource == null){$("#resources_t").removeClass("hid")};
            if($("#case_name").val() == "" || $.cookie("img") == null || $.cookie("img") == "null" || $.cookie("imgs") == null || $.cookie("imgs") == "null"){
                alert("请补全信息");
            }else{*/
                $.post("<?php echo $this->createUrl("background/host_self_info_edit");?>",data,function(){
                    $.cookie('img',null); 
                    $.cookie('imgs',null); 
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=<?php echo $_GET['CI_Type']?>";
                });
            /*};*/  
        });
    })
</script>
</body>
</html>