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
                    <span class="tip hid" style="float:right;">请上传产品图片</span>
                </li>
            </ul>
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
                        <input class="input_in" type="text" value="请输入标题">
                    </div>
                    <span class="left tip hid">请填写产品名称</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">描述:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <textarea class="input_in" name="" id="" cols="30" rows="10">请输入视频描述</textarea>
                    </div>
                    <span class="left tip hid">请填写产品描述</span>
                </li>
                <!-- <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">选择类别：</label>
                    </div>
                    <div class="select_c left">
                        <select name="" id="">
                            <option value="">请选择</option>
                            <option value="">请选择2</option>
                            <option value="">请选择</option>
                        </select>
                    </div>
                    <span class="left add add_class">新增产品类别</span>
                    <span class="left tip">请填写信息标题</span>
                </li> -->
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">供应商：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="select_c left">
                        <select name="" id="">
                            <option value="">请选择</option>
                    <?php foreach ($supplier as $key => $value) {?>
                            <option value=""><?php echo $value['name']?></option>
                    <?php }?>
                        </select>
                    </div>
                    <span class="left add add_supplier">新增供应商</span>
                    <span class="left tip hid">请选择供应商</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">选择单位：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="select_c left">
                        <select name="" id="">
                            <option value="">个</option>
                            <option value="">只</option>
                            <option value="">组</option>
                            <option value="">天</option>
                        </select>
                    </div>
                    <span class="left tip hid">请选择单位</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">售价：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left has_unit">
                        <input class="input_in" type="text" value="请输入标题"><span>元</span>
                    </div>
                    <span class="left tip hid">请填写产品售价</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">底价：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left has_unit">
                        <input class="input_in" type="text" value="请输入标题"><span>元</span>
                    </div>
                    <span class="left tip hid">请填写产品底价</span>
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

        <ul class="desc_box descform_list">
            
        </ul>

        <div class="upload_btn_box">
            <button href="javascript:;" class="btn active">添加产品</button>
            <!-- <a href="javascript:;" class="btn">保存</button> -->
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
                <h2 class="left">弹框标题</h2>
                <img class="right close" src="images/close.jpg" alt="">
            </div>
            <div class="con clearfix">
                <div class="clearfix">
                    <label for="">售价：</label><input class="inputItem" type="text">
                </div>
                <div class="clearfix">
                    <label for="">售价售价：</label><input class="inputItem" type="text">
                </div>
                <div class="btn_box right">
                <button>保存</button>
                <button>不保存</button>
                <button class="close">取消</button>
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
</body>

</html>