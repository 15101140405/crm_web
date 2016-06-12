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
    <script type="text/javascript" src="js/upload.js"></script>
    <!-- 引用控制层插件样式 -->
    <!-- <link rel="stylesheet" href="css/zyUpload.css" type="text/css"> -->
    <script type="text/javascript">
        var swfu;
        var swfu2;
        var uploadsite = "http://file.cike360.com";
        window.onload = function () {
            $.cookie("imgs", "");
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
            
            var settings3= {
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
                button_placeholder_id: "uploadsingle1",
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
                upload_success_handler: uploadSuccess3,
                upload_complete_handler: uploadComplete,
                file_dialog_start_handler: fileDialogStart,
            };
            swfu2 = new SWFUpload(settings2);
            swfu3 = new SWFUpload(settings3);
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
    <div class="upload_wapper" style="margin-bottom:170px;margin-top:80px;">
        <div class="video_desc clearfix">
            <ul class="left desc_box">
        <?php if(!empty($product)){?>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">报价名称:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="<?php echo $product['product_name']?>" placeholder="请输入产品名称" id="name">
                    </div>
                    <span class="left tip hid" id="name_t">请填写产品名称</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">价格:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="<?php echo $product['price']?>" placeholder="请输入产品名称" id="price">
                    </div>
                    <span class="left tip hid" id="name_t">请输入产品价格</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">底价:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="<?php echo $product['cost']?>" placeholder="请输入产品名称" id="cost">
                    </div>
                    <span class="left tip hid" id="name_t">请输入产品底价</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">单位:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="<?php echo $product['unit']?>" placeholder="请输入产品名称" id="unit">
                    </div>
                    <span class="left tip hid" id="name_t">请填写产品单位</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">描述:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="<?php echo $product['description']?>" placeholder="请输入产品名称" id="description">
                    </div>
                    <span class="left tip hid" id="name_t">请填写产品描述</span>
                </li>
        <?php }else{?>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">报价名称:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="" placeholder="请输入产品名称" id="name">
                    </div>
                    <span class="left tip hid" id="name_t">请填写产品名称</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">价格:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="" placeholder="请输入产品名称" id="price">
                    </div>
                    <span class="left tip hid" id="name_t">请输入产品价格</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">底价:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text"  placeholder="请输入产品名称" id="cost">
                    </div>
                    <span class="left tip hid" id="name_t">请输入产品底价</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">单位:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="" placeholder="请输入产品名称" id="unit">
                    </div>
                    <span class="left tip hid" id="name_t">请填写产品单位</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">描述:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="" placeholder="请输入产品名称" id="description">
                    </div>
                    <span class="left tip hid" id="name_t">请填写产品描述</span>
                </li>
        <?php }?>
            </ul>
            <div class="right video_cover" style="position: absolute;right: 120px;">
                <div class="cover_box">
                    <img src="images/cover.jpg" id="poster_img" style="width:120px;">
                </div>
                <button id="uploadsingle">上传产品示意图</button>
                <!--  -->
                <span class="tip tip2 hid" id="ref_pic_t">请上传示意图</span>
            </div>
        </div>


        <div class="upload_btn_box">
        <?php if(!empty($product)){?>
            <button href="javascript:;" service-product-id='<?php echo $product['id']?>' class="btn active" id="insert">确定</button>
        <?php }else{?>
            <button href="javascript:;" class="btn active" id="insert">确定</button>
        <?php }?>    
            <button href="javascript:;" class="btn" id="b">返回</button>
            <!-- <a href="javascript:;" class="btn" id="back">返回</a> -->
        </div>
    </div>
    <!--底部-->
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

<!-- 引用核心层插件 -->
<!-- <script type="text/javascript" src="js/zyFile.js"></script> -->
<!-- 引用控制层插件 -->
<!-- <script type="text/javascript" src="js/zyUpload.js"></script> -->
<!-- 引用初始化JS -->
<!-- <script type="text/javascript" src="js/demo.js"></script> -->
<script>
    $(function(){
        //新增产品
        $("#insert").on("click",function(){
            var data = {
        <?php if(!empty($product)){?>
                id : $(this).attr("service-product-id"),
        <?php }?>
                product_name : $("#name").val(),
                price : $("#price").val(),
                unit : $("#unit").val(),
                description : $("#description").val(),
                CI_ID : <?php echo $_GET['ci_id']?>,
                service_person_id : <?php echo $_GET['service_person_id']?>,
                service_type : <?php echo $_GET['service_type']?>,
            };
        <?php if(!isset($_GET['service_product_id'])){?>
            $.post("<?php echo $this->createUrl("background/host_product_edit");?>",data,function(){
                location.href = "<?php echo $this->createUrl("background/edit_product");?>&ci_id=<?php echo $_GET['ci_id']?>&service_person_id=<?php echo $_GET['service_person_id']?>";
            });
        <?php }else{?>
            $.post("<?php echo $this->createUrl("background/edit_host_product");?>",data,function(){
                location.href = "<?php echo $this->createUrl("background/edit_product");?>&ci_id=<?php echo $_GET['ci_id']?>&service_person_id=<?php echo $_GET['service_person_id']?>";
            });
        <?php }?>
        });

        //返回
        $("#b").on("click",function(){
            $.cookie('img', null); 
            $.cookie('img1', null); 
            location.href="<?php echo $this->createUrl("background/edit_product");?>&ci_id=<?php echo $_GET['ci_id']?>&service_person_id=<?php echo $_GET['service_person_id']?>";
        });
    })
</script>
</body>

</html>