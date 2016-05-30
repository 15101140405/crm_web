<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no">
    <title>上传</title>
    <link rel="stylesheet" type="text/css" href="css/base_background.css" />
    <link rel="stylesheet" type="text/css" href="css/layout.css" />
    <link rel="stylesheet" type="text/css" href="css/upload_set.css" />
</head>

<body style="background:#fff;">
    <!--头部-->
    <div class="upload_top">
        <div class="upload_wapper clearfix">
            <h1 class="logo left"><img src="images/logo.jpg" alt=""></h1>
            <span class="nick right">best</span>
        </div>
    </div>
    <!--导航-->
    <div class="nav_area">
        <div class="upload_wapper">
            <ul class="nav_list upload_wapper clearfix">
                <li ><a href="#">场地布置</a>
                    <ul class="sub_nav_list" id="decoration">
                        <li tap-id="0"><a href="#">全部</a>
                        </li>
                <?php foreach ($decoration_tap as $key => $value) {?>
                        <li tap-id="<?php echo $value['id']?>"><a href="#"><?php echo $value['name']?></a>
                        </li>
                <?php }?>
                    </ul>
                </li>
                <li>|</li>
                <li id="host"><a href="#">主持</a>
                </li>
                <li>|</li>
                <li id="video"><a href="#">摄像</a>
                </li>
                <li>|</li>
                <li id="camera"><a href="#">摄影</a>
                </li>
                <li>|</li>
                <li id="makeup"><a href="#">化妆</a>
                </li>
                <li>|</li>
                <li id="other"><a href="#">其他人员</a>
                </li>
                <li>|</li>
                <li id="lss"><a href="#">灯光／音响／视频</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="upload_set_c upload_wapper clearfix">
        <!--左侧内容区域-->
        <div class="left_area left">
            <ul class="goods_list clearfix" id="product">
        <?php foreach ($supplier_product as $key => $value) {
            if($value['supplier_type_id'] == 20 || $value['supplier_type_id'] == 3 || $value['supplier_type_id'] == 4 || $value['supplier_type_id'] == 5 || $value['supplier_type_id'] == 6 || $value['supplier_type_id'] == 7 || $value['supplier_type_id'] == 8 || $value['supplier_type_id'] == 9 || $value['supplier_type_id'] == 23){?>
                <li style="height: 200px;" tap="<?php echo $value['decoration_tap']?>" supplier-type-id="<?php echo $value['supplier_type_id']?>" product-id="<?php echo $value['id']?>" unit-cost="<?php echo $value['unit_cost']?>">
                    <div class="img_box" style="height:60%">
                        <img src="<?php echo "http://file.cike360.com".$value['ref_pic_url']?>" alt="">
                        <!-- <span>已售233件</span> -->
                    </div>
                    <div class="info_box" style="height:40%">
                        <p class="name"><?php echo $value['name']?></p>
                        <p class="price">&yen;<strong><?php echo $value['unit_price']?></strong>
                        </p>
                        <!-- <p class="original_price">&yen;<del>400.00</del>
                        </p> -->
                        <button class="add_product">加入套系</button>
                    </div>
                </li>
        <?php }}?>
            </ul>
        </div>
        <!--右侧内容区域-->
        <div class="right_area right" style="background:#fff;width:247px;">
            <div>
                <div class="tit_box clearfix" style="width:253px;background:#fff;border-bottom: 1px solid #e6e6e6;">
                    <h2 class="left">折扣：</h2>
                    <input class="input_in" id="feast_discount" style="height: 30px;margin-top: 5px;border: 0;" type="text" value="" placeholder="请输入折扣，如0.8">
                    <!-- <a href="#" class="right">查看更多</a> -->
                </div>
                <ul class="add_list" style="width:230px;" id="shopping_car">
            <?php foreach ($product_list as $key => $value) {?>
                    <li class="clearfix hid new_hid" product-id="<?php echo $value['product_id']?>" unit-cost="<?php echo $value['cost']?>" style="display: list-item;">
                        <img class="left product_pic" src="http://file.cike360.com/upload/2r20160516111246.jpg" alt="">
                        <div class="con left">
                            <h3 class="product_name"></h3>
                            <div class="counter_box clearfix">
                                <span class="minus_btn btn disabled left">-</span>
                                <input class="count left amount" type="text" readonly="true" value="<?php echo $value['amount']?>">
                                <span class="add_btn btn left">+</span>
                            </div>
                        </div>
                        <img src="images/close.png" class="del_product" style="width: 10px;height: 10px;float: right;margin-right:0;margin-bottom:5px">
                        <p class="right unit_price" style="margin-top: 5px;margin-right: 15px;">¥<input class="product_price" type="text" value="<?php echo $value['price']?>"></p>
                    </li>
            <?php }?>
                </ul>
                 
            </div>
           <div class="button_box" id="create">
                    &yen;
                    <span id="total_price">0</span> 下一步
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
<script type="text/javascript" src="js/upload_set.js"></script>
<script>
    $(function(){

        //初始渲染
        $("#product li").addClass("hid");
        $("[supplier-type-id='20']").removeClass("hid");
        $("#shopping_car li").each(function(){
            var product = $("#product [product-id='"+$(this).attr('product-id')+"']");
            $(this).find(".product_id").html(product.find(".name").html());
            $(this).find(".product_pic").attr("src",product.find("img").attr("src"));
            total_price(); 
        });

        //点击加入套系
        $(".add_product").on("click",function(){
            var data = $(this).parent().parent()
            html = '<li class="clearfix hid new_hid" product-id="'+data.attr('product-id')+'" unit-cost="'+data.attr('unit-cost')+'">'+
                        '<img class="left" src="'+data.find('img').attr('src')+'" alt="">'+
                        '<div class="con left">'+
                            '<h3>'+data.find('.name').html()+'</h3>'+
                            '<div class="counter_box clearfix">'+
                                '<span class="minus_btn btn disabled left">-</span>'+
                                '<input class="count left amount" type="text" readonly="true" value="1">'+
                                '<span class="add_btn btn left">+</span>'+
                            '</div>'+
                        '</div>'+
                        '<img src="images/close.png" class="del_product" style="width: 10px;height: 10px;float: right;margin-right:0;margin-bottom:5px"></img>'+
                        '<p class="right unit_price" style="margin-top: 5px;margin-right: 15px;">¥'+
                            '<input class="product_price" type="text" value="'+data.find('.price').find("strong").html()+'">'+
                        '</p>'+
                    '</li>';
            $("#shopping_car").prepend(html);
            $(".new_hid").fadeIn();
            total_price();
        });

        //场地布置筛选
        $("#decoration li").on("click",function(){
            $("#product li").removeClass("hid");
            $("#product li").addClass("hid");
            var tap = $(this).attr("tap-id");
            if(tap != 0){$("[tap='"+tap+"']").removeClass("hid")}else{$("#product li").removeClass("hid");$("#product li").addClass("hid");$("[supplier-type-id='20']").removeClass("hid");};
            $(".sub_nav_list").hide();
        });

        //主持
        $("#host").on("click",function(){
            $("#product li").removeClass("hid");
            $("#product li").addClass("hid");
            $("[supplier-type-id='3']").removeClass("hid");
        });

        //摄像
        $("#video").on("click",function(){
            $("#product li").removeClass("hid");
            $("#product li").addClass("hid");
            $("[supplier-type-id='4']").removeClass("hid");
        });

        //摄影
        $("#camera").on("click",function(){
            $("#product li").removeClass("hid");
            $("#product li").addClass("hid");
            $("[supplier-type-id='5']").removeClass("hid");
        });

        //化妆
        $("#makeup").on("click",function(){
            $("#product li").removeClass("hid");
            $("#product li").addClass("hid");
            $("[supplier-type-id='6']").removeClass("hid");
        });
        //其他人员
        $("#other").on("click",function(){
            $("#product li").removeClass("hid");
            $("#product li").addClass("hid");
            $("[supplier-type-id='7']").removeClass("hid");
        });
        //灯光／音响／视频
        $("#lss").on("click",function(){
            $("#product li").removeClass("hid");
            $("#product li").addClass("hid");
            $("[supplier-type-id='8']").removeClass("hid");
            $("[supplier-type-id='9']").removeClass("hid");
            $("[supplier-type-id='23']").removeClass("hid");
        });


        //点击创建套系
        $("#create").on("click",function(){
            var product_list = "";
            $("#shopping_car li").each(function(){
                product_list += $(this).attr('product-id') +"|"+ $(this).find(".product_price").val() +"|"+ $(this).find(".amount").val() +"|"+ $(this).attr("unit-cost") +",";
            });
            product_list = product_list.substring(0,product_list.length-1);
            location.href = "<?php echo $this->createUrl("background/edit_set2");?>&ci_id=<?php echo $_GET['ci_id']?>&ct_id=<?php echo $_GET['ct_id']?>&product_list=" +product_list+ "&final_price=" +$("#total_price").html();
        })

        //改变数量、单价时，刷新总价
        $('.product_price').live('change', function() {
            total_price(); 
        });
        $(".del_product").live("click",function(){
            $(this).parent().remove();
            total_price(); 
        });
        $(".shopping_car").find(".counter_box").count({limitnum:5});
        $(".add_btn").live("click",function(){
            total_price(); 
        });
        $(".minus_btn").live("click",function(){
            total_price(); 
        });
        
        //总价计算，并刷新
        function total_price(){
            var total_price = 0;
            $("#shopping_car li").each(function(){
                total_price += $(this).find(".count").val() * $(this).find(".product_price").val();
            });
            $("#total_price").html(total_price);
        };
    })
</script>
</body>

</html>