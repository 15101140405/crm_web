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
            /*swfu3 = new SWFUpload(settings3);*/
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
    <div class="upload_wapper" style="margin-bottom:170px;margin-top:80px">
        <div class="video_desc clearfix">
            <ul class="left desc_box">
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">产品名称:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="" placeholder="请输入产品名称" id="name">
                    </div>
                    <span class="left tip hid" id="name_t">请填写产品名称</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">供应商：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="select_c left">
                        <select name="" id="supplier">
                            <option value="">请选择</option>
                    <?php foreach ($supplier as $key => $value) {?>
                            <option value="<?php echo $value['name']?>[<?php echo $value['supplier_type_name']?>]" supplier-id="<?php echo $value['id']?>" supplier-type-id="<?php echo $value['type_id']?>"><?php echo $value['name']?>[<?php echo $value['supplier_type_name']?>]</option>
                    <?php }?>
                        </select>
                    </div>
                    <span class="left add add_supplier">新增<灯光／音响／视频>供应商</span>
                    <span class="left tip hid" id="supplier_t">请选择供应商</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">选择单位：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="select_c left">
                        <select name="" id="unit">
                            <option value="个">个</option>
                            <option value="只">只</option>
                            <option value="组">组</option>
                            <option value="天">天</option>
                            <option value="米">米</option>
                            <option value="米">平米</option>
                            <option value="米">套</option>
                        </select>
                    </div>
                    <span class="left tip hid" id="unit_t">请选择单位</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">售价：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left has_unit">
                        <input class="input_in" type="text" value="" placeholder="请输入产品售价" id="price"><span>元</span>
                    </div>
                    <span class="left tip hid" id="price_t">请填写产品售价</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">底价：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left has_unit">
                        <input class="input_in" type="text" value="" placeholder="请输入产品底价" id="cost"><span>元</span>
                    </div>
                    <span class="left tip hid" id="cost_t">请填写产品底价</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">描述:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <textarea class="input_in" name="" id="remarks" cols="30" placeholder="请输入产品描述" rows="10"></textarea>
                    </div>
                    <span class="left tip hid" id="remarks_t">请填写产品描述</span>
                </li>
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
            <button href="javascript:;" class="btn active" id="sure">添加产品</button>
            <button href="javascript:;" class="btn" id="b">返回</button>
        </div>
    </div>
    <!--弹层2-->
    <div class="msgbox msgbox_supplier">
        <div class="msgbox_c">
            <div class="tit_box clearfix">
                <h2 class="left">新增<场地布置>供应商</h2>
                <img class="right close" src="images/close.jpg" alt="">
            </div>
            <div class="con clearfix">
                <div class="clearfix">
                    <label for="">姓名</label><input id="supplier_name" class="inputItem" type="text">
                </div>
                <div class="clearfix">
                    <label for="">手机号：</label><input id="telephone" class="inputItem" type="text">
                </div>
                <div class="clearfix">
                    <label for="">供应商类别：</label>
                    <div class="select_c left" id="supplier_type">
                        <select name="" id="unit">
                            <option value="灯光" supplier-type="8">灯光</option>
                            <option value="音响" supplier-type="23">音响</option>
                            <option value="视频" supplier-type="9">视频</option>
                        </select>
                    </div>
                </div>
                <div class="btn_box right">
                    <button id="insert_supplier">保存</button>
                </div>
            </div>
            
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
        //添加产品
        $("#sure").on("click",function(){
            var mydate = new Date();
            var time = mydate.toLocaleDateString();
            var data = {
                name : $("#name").val(),
                description : $("#remarks").val(),
                supplier_id : $("#supplier option:selected").attr("supplier-id"),
                supplier_type_id : $("#supplier option:selected").attr("supplier-type-id"),
                decoration_tap : "",
                dish_type : 0,
                standard_type : 0,
                category : 2,
                unit : $("#unit option:selected").val(),
                unit_price : $("#price").val(),
                unit_cost : $("#cost").val(),
                service_charge_ratio : 0,
                ref_pic_url : $.cookie('img'),
                update_time : time
            };
            console.log(data);
            $(".tip").removeClass("hid");
            $(".tip").addClass("hid");
            if(data.name==""){$("#name_t").removeClass("hid")};
            if(data.decription==""){$("#remarks_t").removeClass("hid")};
            if(data.supplier_id==null || data.supplier_type_id==null){$("#supplier_t").removeClass("hid")};
            if(data.decoration_tap==null){$("#tap_t").removeClass("hid")};
            if(data.unit==""){$("#unit_t").removeClass("hid")};
            if(data.unit_price==""){$("#price_t").removeClass("hid")};
            if(data.unit_cost==""){$("#cost_t").removeClass("hid")};
            if(data.ref_pic_url==""){$("#file_t").removeClass("hid")};
            if($.cookie('img')== "null" || $.cookie('img')== null){$("#ref_pic_t").removeClass("hid")};
            if($.cookie('img')== "null" || $.cookie('img')== null || data.name==""||data.decription==""||data.supplier_id==null||data.supplier_type_id ==null||data.unit==""||data.unit_price==""||data.unit_cost==""||data.ref_pic_url==""||data.decoration_tap==null){
                alert("请补全信息！");
            }else{
                $.post("<?php echo $this->createUrl("background/product_upload");?>",data,function(){
                    $.cookie('img', null); 
                    $.cookie('img1', null); 
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=7";
                });
            };
        });

        //增加供应商
        $("#insert_supplier").on("click",function(){
            var mydate = new Date();
            var time = mydate.toLocaleDateString();
            var data = {
                name : $("#supplier_name").val(),
                telephone : $("#telephone").val(),
                supplier_type : $("#supplier_type option:selected").attr("supplier-type"),
                update_time : time
            };
            /*if(data.)*/
            $.post("<?php echo $this->createUrl("background/supplier_add");?>",data,function(){
                location.href="<?php echo $this->createUrl("background/upload_product_lss");?>";
            });
        });

        //增加产品类别
        $("#insert_tap").on("click",function(){
            var mydate = new Date();
            var time = mydate.toLocaleDateString();
            var data = {
                account_id : $.cookie('account_id'),
                name : $("#tap_name").val(),
                pic : $.cookie('img1'),
                update_time : time
            };
            console.log(data);
            $(".tip").removeClass("hid");
            $(".tip").addClass("hid");
            if($("#tap_name").val() == ""){$("#tap_name_t").removeClass("hid")};
            if($.cookie('img1') == 'null' || $.cookie('img1') == null){$("#tap_poster_t").removeClass("hid")};
            if($("#tap_name").val() == "" || $.cookie('img1') == 'null' || $.cookie('img1') == null){
                alert('请补全信息');
            }else{
                $.post("<?php echo $this->createUrl("background/tap_add");?>",data,function(){
                    $.cookie('img1', null); 
                    location.href = "<?php echo $this->createUrl("background/upload_product_lss");?>";
                });
            };
        });

        //返回
        $("#b").on("click",function(){
            $.cookie('img', null); 
            $.cookie('img1', null); 
            location.href="<?php echo $this->createUrl("background/index");?>&CI_Type=7";
        });
    })
</script>
</body>

</html>