<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>上传</title>
    <link rel="stylesheet" type="text/css" href="css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <!-- 引用控制层插件样式 -->
    <link rel="stylesheet" href="css/zyUpload.css" type="text/css">
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
    <div class="upload_wapper" style="margin-bottom:170px;">
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
                        <label for="">类别：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="select_c left">
                        <select name="" id="tap">
                            <option value="">请选择</option>
                    <?php foreach ($decoration_tap as $key => $value) {?>
                            <option value="<?php echo $value['name']?>" tap-id="<?php echo $value['id']?>"><?php echo $value['name']?></option>
                    <?php }?>
                        </select>
                    </div>
                    <span class="left add add_supplier">新增类别</span>
                    <span class="left tip hid" id="tap_t">请选择供应商</span>
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
                            <option value="<?php echo $value['name']?>" supplier-id="<?php echo $value['id']?>" supplier-type-id="<?php echo $value['type_id']?>"><?php echo $value['name']?></option>
                    <?php }?>
                        </select>
                    </div>
                    <span class="left add add_supplier">新增<场地布置>供应商</span>
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
            <!-- <div class="right video_cover">
                <div class="cover_box">
                    <img src="images/cover.jpg" alt="">
                </div>
                <button>上传视频封面</button>
                <span class="tip tip2">请填写信息标题</span>
            </div> -->
        </div>

        <!--产品图片-->
        <div class="upload_wapper">
            <div class="video_desc clearfix">
                <ul class="left desc_box">
                    <li class="desc_item clearfix">
                        <div class="tit_box left">
                            <label for="">产品图片:</label>
                            <span class="must">*</span>
                        </div>
                        <div id="demo" class="demo" style="display:inline-block;">
                            <form id="uploadForm" action="/upload/UploadAction" method="post" enctype="multipart/form-data">
                                <div class="upload_box">
                                    <div class="upload_main">
                                        <div class="upload_choose">
                                            <div class="convent_choice">
                                                <div class="andArea">
                                                    <div class="filePicker">点击选择文件</div>
                                                    <input id="fileImage" type="file" size="30" name="fileselect[]" multiple=""> </div>
                                            </div>
                                        </div>
                                        <div class="status_bar">
                                            <div id="status_info" class="info"></div>
                                            <div class="btns">
                                                <div class="webuploader_pick">继续选择</div>
                                                <div class="upload_btn1">开始上传</div>
                                            </div>
                                        </div>
                                        <div id="preview" class="upload_preview">

                                        </div>
                                    </div>
                                    
                                    <div class="upload_submit">
                                        <button type="button" id="fileSubmit" class="upload_submit_btn">确认上传文件</button>
                                    </div>
                                    <div id="uploadInf" class="upload_inf"></div>
                                </div>
                            </form>
                        </div>
                        <span class="tip hid" style="float:right;" id="file_t">请上传产品图片</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="upload_btn_box">
            <button href="javascript:;" class="btn active" id="sure">添加产品</button>
            <button href="javascript:;" class="btn" id="b">返回</button>
            <!-- <a href="javascript:;" class="btn" id="back">返回</a> -->
        </div>
    </div>
    <!--弹层1-->
    <div class="msgbox msgbox_class">
        <div class="msgbox_c">
            <div class="tit_box clearfix">
                <h2 class="left">弹框标题</h2>
                <img class="right close" src="images/close.jpg" alt="">
            </div>
            <div class="con clearfix">
                <div class="clearfix">
                    <label for="">售价：</label><input class="inputItem" type="text">
                </div>
                <div class="btn_box right">
                <button>保存</button>
                <button>不保存</button>
                <button class="close">取消</button>
            </div>
            </div>
            
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
                <!-- <div class="clearfix">
                    <label for="">供应商类别：</label>
                    <div class="select_c left">
                        <select name="" id="supplier_type" style="height: 30px;width: 235px;margin-top: 5px;">
                            <option value="">请选择</option>
                    <?php /*foreach ($supplier_type as $key => $value)*/ {?>
                            <option value="" supplier-type-id=""></option>
                    <?php }?>
                        </select>
                    </div>
                </div> -->
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
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="js/input.js"></script>
<script type="text/javascript" src="js/upload.js"></script>
<!-- 引用核心层插件 -->
<script type="text/javascript" src="js/zyFile.js"></script>
<!-- 引用控制层插件 -->
<script type="text/javascript" src="js/zyUpload.js"></script>
<!-- 引用初始化JS -->
<script type="text/javascript" src="js/demo.js"></script>
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
                decoration_tap : $("#tap option:selected").attr("tap-id"),
                standard_type : 0,
                category : 2,
                unit : $("#unit option:selected").val(),
                unit_price : $("#price").val(),
                unit_cost : $("#cost").val(),
                service_charge_ratio : 0,
                ref_pic_url : $("#fileImage").val(),
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
            if(data.name==""||data.decription==""||data.supplier_id==null||data.supplier_type_id ==null||data.unit==""||data.unit_price==""||data.unit_cost==""||data.ref_pic_url==""||data.decoration_tap==null){
                alert("请补全信息！");
            }else{
                $.post("<?php echo $this->createUrl("background/product_upload");?>",data,function(){
                    location.href = "<?php echo $this->createUrl("background/index");?>&CI_Type=4";
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
                type_id : $("#supplier_type option:selected").val(),
                update_time : time
            };
            $.post("<?php echo $this->createUrl("background/supplier_add");?>",data,function(){
                location.href="<?php echo $this->createUrl("background/upload_product");?>";
            });
        });

        //返回
        $("#b").on("click",function(){
            location.href="<?php echo $this->createUrl("background/index");?>&CI_Type=4";
        })
    })
</script>
</body>

</html>