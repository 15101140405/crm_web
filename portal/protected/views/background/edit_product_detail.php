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
                        <label for="">产品名称:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left clearfix">
                        <input class="input_in" type="text" value="<?php echo $product['product_name']?>" placeholder="请输入产品名称" id="name">
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
                    <span class="left add add_class">新增类别</span>
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
                            <option value="米">米</option>
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
                        <input class="input_in" type="text" value="<?php echo $product['unit_price']?>" placeholder="请输入产品售价" id="price"><span>元</span>
                    </div>
                    <span class="left tip hid" id="price_t">请填写产品售价</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">底价：</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left has_unit">
                        <input class="input_in" type="text" value="<?php echo $product['unit_cost']?>" placeholder="请输入产品底价" id="cost"><span>元</span>
                    </div>
                    <span class="left tip hid" id="cost_t">请填写产品底价</span>
                </li>
                <li class="desc_item clearfix">
                    <div class="tit_box left">
                        <label for="">描述:</label>
                        <span class="must">*</span>
                    </div>
                    <div class="input_box left">
                        <textarea class="input_in" name="" id="remarks" cols="30" placeholder="请输入产品描述" rows="10"><?php echo $product['description']?></textarea>
                    </div>
                    <span class="left tip hid" id="remarks_t">请填写产品描述</span>
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
        </div>


        <div class="upload_btn_box">
            <button href="javascript:;" class="btn active" id="insert">确定</button>
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
                product_name : $("#name").val(),
                price : $("#price").val(),
                unit : $("#unit").val(),
                description : $("#description").val(),
                CI_ID : <?php echo $_GET['ci_id']?>,
                service_person_id : <?php echo $_GET['service_person_id']?>,
                service_type : <?php echo $_GET['service_type']?>,
            };
            $.post("<?php echo $this->createUrl("background/host_product_edit");?>",data,function(){
                location.href = "<?php echo $this->createUrl("background/edit_product");?>&ci_id=<?php echo $_GET['ci_id']?>&service_person_id=<?php echo $_GET['service_person_id']?>";
            });
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