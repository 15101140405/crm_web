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
    <!-- 已上传资源 -->
        <div class="index_con_box" style="margin-bottom:170px;margin-top:80px;">
            <div class="con">
                <ul class="upload_list" id="resources_list">
            <?php if(!empty($product)) {
                    foreach ($product as $key => $value) {?>
                    <li class="clearfix" >
                        <div class="upload_con_box left clearfix">
                            <div class="video_info left">
                                <h3><?php echo $value['product_name']?></h3>
                                <div class="state_box clearfix">
                                    <img class="left" src="images/up06.jpg" alt="">
                                    <span class="left">描述</span>
                                    <span class="from left"><?php echo $value['description']?></span>
                                </div>
                                <!-- <p class="tag">标签:<span>分销</span>
                                </p> -->
                            </div>
                        </div>
                        <div class="edit_btn_box right clearfix" style="width:50%">
                            <span class="left state" style="float: left;margin-right: 0;">报价：<?php echo $value['price']?>元／<?php echo $value['unit']?></span>
                            <div class="edit_btn left del_resource del" service-product-id="<?php echo $value['id']?>" style="float: right;margin-top: 25px;background-color:red;" href="javascript:;">删除</div>
                            <div class="edit_btn left del_resource edit" service-product-id="<?php echo $value['id']?>" style="float: right;margin-top: 25px;" href="javascript:;">编辑</div>
                        </div>
                    </li>
            <?php }}?>
                </ul>
            </div>
            <div class="upload_btn_box">
                <button href="javascript:;" class="btn active" id="insert">新增报价</button>
                <button href="javascript:;" class="btn" id="b">返回</button>
                <!-- <a href="javascript:;" class="btn" id="back">返回</a> -->
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
        //新增产品
        $("#insert").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_product_detail");?>&service_person_id=<?php echo $_GET['service_person_id']?>&service_type=3&ci_id=<?php echo $_GET['ci_id']?>";
        });

        //返回
        $("#b").on("click",function(){
            $.cookie('img', null); 
            $.cookie('img1', null); 
            location.href="<?php echo $this->createUrl("background/index");?>&CI_Type=<?php echo $_GET['CI_Type']?>";
        });

        //编辑
        $(".edit").on("click",function(){
            location.href = "<?php echo $this->createUrl("background/edit_product_detail");?>&service_person_id=<?php echo $_GET['service_person_id']?>&service_type=3&ci_id=<?php echo $_GET['ci_id']?>&service_product_id="+$(this).attr("service-product-id");
        });

        //删除
        $(".del").on("click",function(){
            $.post("<?php echo $this->createUrl("background/del_service_product");?>",{id : $(this).attr("service-product-id")},function(){
                location.reload();
            })
        })
    })
</script>
</body>

</html>