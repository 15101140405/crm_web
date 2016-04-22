<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单填写</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">订单填写</h2>
    </div>
    <div class="ulist_module pad_b40">
        <ul class="ulist">
        <?php 
            if($_GET['type'] == "edit"){
        ?>
            <li class="ulist_item flex">
                折扣价
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="price" 
                           value="<?php echo $orderproduct['actual_price']; ?>"/>
                </div>
                <i class="mar_l10 t_green"><?php echo $productData['unit'];?></i>
            </li>
            <li class="ulist_item flex" id="number">
                数量
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="amount" value="<?php echo $orderproduct['unit']; ?>"
                           placeholder=""/>
                </div>
            </li>
            <li class="ulist_item flex">
                单位成本
                <div class="flex1">
                    <input class="align_r t_green" id="cost" type="text" placeholder="输入单位成本" value="<?php echo $orderproduct['actual_unit_cost'];?>"/>
                </div>
            </li>
            <li class="ulist_item flex" id='fuwufei'>
                服务费
                <div class="flex1">
                    <input class="align_r t_green" type="text" value="<?php echo $orderproduct['actual_service_ratio']; ?>" placeholder="<?php  echo $productData['service_charge_ratio'];?>" id="fee"/>
                </div>
            </li>
            <li class="ulist_item">备注</li> 
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70"  id="remark"><?php  echo $orderproduct['remark'];?></textarea>
                </div>
            </li>
        <?php
            }else {
        ?>
            <li class="ulist_item flex">
                折扣价
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="price" value=""
                           placeholder="标准价格：<?php echo $productData['unit_price']; ?>"/>
                </div>
                <i class="mar_l10 t_green"><?php echo $productData['unit'];?></i>
            </li>
            <li class="ulist_item flex" id="number">
                数量
                <div class="flex1">
                    <input class="align_r t_green" type="text" id="amount" value=""
                           placeholder="请输入数量"/>
                </div>
            </li>
            <li class="ulist_item flex">
                单位成本
                <div class="flex1">
                    <input class="align_r t_green" id="cost" type="text" placeholder="输入单位成本" value="<?php echo $productData['unit_cost'];?>"/>
                </div>
            </li>
            <li class="ulist_item flex" id='fuwufei'>
                服务费
                <div class="flex1">
                    <input class="align_r t_green" id="fuwufei_input" type="text" value="" placeholder="<?php  echo $productData['service_charge_ratio'];?>" id="fee"/>
                </div>
            </li>
            <li class="ulist_item">备注</li> 
            <li class="remark">
                <div class="text_bar">
                    <textarea maxlength="70" placeholder="" id="remark"></textarea>
                </div>
            </li>
        <?php
            }
        ?>
        </ul>
    </div>
    <div class="ulist_module" id="schedule">
        <ul class="ulist charge_list" >
            <li class="ulist_item list_more">
                <div class="item">
                    <p class="name">查看档期</p>
                </div>
            </li>
        </ul>
    </div>
    <!-- <div class="ulist_module pad_b40 hid" id="supplier">
        <div class="module_title" id="title">选择供应商</div>
        <ul class="select_ulist">
            <?php
            $arr_supplier = array( //bacground data 供应商
                '0' => array(
                    'supplier_id' => '1',
                    'name' => '小李',
                    'select' => ''

                ),
                '1' => array(
                    'supplier_id' => '2',
                    'name' => '小张',
                    'select' => ''

                ),
                '2' => array(
                    'supplier_id' => '3',
                    'name' => '小王',
                    'select' => 'select_selected'

                )
            );
            $total = 31000; //bacground data 总价
            foreach ($arr_supplier as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $supplier[$key1] = $value1;
                }

                ?>
                <li class="select_ulist_item select  <?php echo $supplier['select']; ?> "
                    supplier-id="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['name']; ?></li>
            <?php } ?>
        </ul>
    </div> -->
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="insert">提交订单</div>
        <div class="r_btn" id="update">提交订单</div>
        <div class="r_btn" id="del" style="background-color:red;">删除</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>

    //不同页面：初始化、返回、保存（获取、验证数据）、删除

    $(function () {

        //获取用户信息
        var account_id = <?php echo $_SESSION['account_id']?>;

        //页面初始化
        if ($.util.param("type") == "edit") {
            $("#insert").remove();
            //此处用php从后端取数
        } else if ($.util.param("type") == "new") {
            $("#del").remove();
            $("#update").remove();
        }
        ;
        if ($.util.param("tab") == "host") {
            $("#number").remove();
            $(".total").remove();
        }else{
            $("#schedule").remove();
        };
        if($.util.param("tab") != "feast" && $.util.param("tab") != ""){
            $("#fuwufei").remove();
        };

        //查看档期
        $("#schedule").on("click",function(){
            location.href="<?php echo $this->createUrl("service/index");?>&code=&service_team_id=&from=design&supplier_product_id=<?php echo $_GET['product_id']?>";
        });

        //选择供应商
        if ($.util.param("tab") == "other") {
            $(".select_ulist li").on("click", function () {
                if ($(this).hasClass("select_selected")) {
                    $(this).removeClass("select_selected");
                } else {
                    $(this).addClass("select_selected");
                }
            })
        }
        else {
            $(".select_ulist li").on("click", function () {
                $("li.select_ulist_item").removeClass("select_selected");
                $(this).addClass("select_selected");
            })
        }

        //返回按钮
        $(".l_btn").on("click", function () {
            if ($.util.param("tab") == "host" || $.util.param("tab") == "video" || $.util.param("tab") == "camera" || $.util.param("tab") == "makeup" || $.util.param("tab") == "other") {
                location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            } else if ($.util.param("tab") == "lighting" || $.util.param("tab") == "screen") {
                location.href = "<?php echo $this->createUrl("design/lightingScreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            } else if ($.util.param("tab") == "graphic" || $.util.param("tab") == "film") {
                location.href = "<?php echo $this->createUrl("design/graphicFilm", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            } else if ($.util.param("tab") == "dress" || $.util.param("tab") == "appliance") {
                location.href = "<?php echo $this->createUrl("design/dressAppliance", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            } else if ($.util.param("tab") == "drinks" || $.util.param("tab") == "car") {
                location.href = "<?php echo $this->createUrl("design/drinksCar", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }
            ;
        });


        //新增按钮
        $("#insert").on("click", function () {
            var get_info;
            //主持
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            

            if ($.util.param("tab") == "host" ) {
                //赋值 
                
    
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : parseInt($.util.param("product_id")),        
                    'actual_price' : parseInt($("#price").val()),
                    'amount' : 1 ,
                    'unit' : "<?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : $("#cost").val(),
                    'update_time' : update_time,
                    'actual_service_ratio' : "<?php echo $productData['service_charge_ratio']?>",
                    'remark' : $("#remark").val()
                };
                console.log(get_info);

                //填写内容判断
                /*if (get_info.actual_price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (!isNaN(get_info.actual_price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                }*/
            }else if($.util.param("tab") == "feast" ){
                get_info = {
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : parseInt($.util.param("product_id")),        
                    'actual_price' : parseInt($("#price").val()),
                    'amount' : $("#amount").val() ,
                    'unit' : "<?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : $("#cost").val(),
                    'update_time' : update_time,
                    'actual_service_ratio' : parseInt($("#fuwufei_input").val()),
                    'remark' : $("#remark").val()
                };
                console.log(get_info);
            }


            //摄影、化妆、灯光定制、视频、平面设计、视频设计、婚纱、婚品、酒水、车辆
            else {
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : parseInt($.util.param("product_id")),         
                    'actual_price' : parseInt($("#price").val()),
                    'amount' : $("#amount").val(),
                    'unit' :" <?php echo $productData['unit'] ?>",
                    'actual_unit_cost' :  $("#cost").val(),
                    'update_time' : update_time,
                    'actual_service_ratio' : "<?php echo $productData['service_charge_ratio']?>",
                    'remark' : $("#remark").val()
                };

                //填写内容判断
                /*if (get_info.actual_price == "" || get_info.actual_price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (!isNaN(get_info.actual_price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                } else if (!isNaN(get_info.amount)) {
                    alert("请输入正确的数量信息！");
                    return false;
                }*/
            }
            //其他
            /*else if ($.util.param("tab") == "other") {
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    
                    account_id : account_id ,
                    order_id : localStorage.getItem("order_id") ,
                    product_id : $.util.param("product_id"),         
                    actual_price : $("#price").val() ,
                    amount : $("#amount").val(),,
                    unti : <?php echo $productData['unit'] ?>,
                    actual_unit_cost : <?php echo $productData['unit_cost'] ?> ,
                    update_time : <?php echo date('y-m-d h:i:s',time()) ?> ,
                    actual_service_ratio : <?php echo $productData['service_charge_ratio']?>
                }

                //填写内容判断
                if (get_info.price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                }
                ;
            }
            //灯光
            else if ($.util.param("tab") == "lighting") {
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    
                    account_id : account_id ,
                    order_id : localStorage.getItem("order_id") ,
                    product_id : $.util.param("product_id"),         
                    actual_price : $("#price").val() ,
                    amount : $("#amount").val(),,
                    unti : <?php echo $productData['unit'] ?>,
                    actual_unit_cost : <?php echo $productData['unit_cost'] ?> ,
                    update_time : <?php echo date('y-m-d h:i:s',time()) ?> ,
                    actual_service_ratio : <?php echo $productData['service_charge_ratio']?>
                };*/

                //填写内容判断
                if (get_info.actual_price == "" || get_info.amount == "" || get_info.unti == "" || get_info.price == "" ) {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.actual_price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                }
                ;
            

            $.post("<?php echo $this->createUrl('design/savetp');?>",get_info,function(data){  //bacground data
                alert("提交成功!");
                if ($.util.param("tab") == "host" || $.util.param("tab") == "video" || $.util.param("tab") == "camera" || $.util.param("tab") == "makeup" || $.util.param("tab") == "other") {
                    location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "lighting" || $.util.param("tab") == "screen") {
                    location.href = "<?php echo $this->createUrl("design/lightingScreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "made") {
                    location.href = "<?php echo $this->createUrl("design/lightingMadeDetail", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "graphic" || $.util.param("tab") == "film") {
                    location.href = "<?php echo $this->createUrl("design/graphicFilm", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "dress" || $.util.param("tab") == "appliance") {
                    location.href = "<?php echo $this->createUrl("design/dressAppliance", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "drinks" || $.util.param("tab") == "car") {
                    location.href = "<?php echo $this->createUrl("design/drinksCar", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                }else if ($.util.param("tab") == "feast") {
                    location.href = "<?php echo $this->createUrl("design/feast", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                }else{
                    location.href = "<?php echo $this->createUrl('product/store');?>&code=&account_id=<?php echo $_SESSION['account_id']?>&staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>";
                }
            });
        });

        //编辑按钮
        $("#update").on("click", function () {
            var get_info;
            //主持
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            

            if ($.util.param("tab") == "host" ) {
                //赋值 
                
    
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : parseInt($.util.param("product_id")),        
                    'actual_price' : parseFloat($("#price").val()),
                    'amount' : 1 ,
                    'unit' : "<?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : $("#cost").val(),
                    'update_time' : update_time,
                    'actual_service_ratio' : "<?php echo $productData['service_charge_ratio']?>",
                    'remark' : $("#remark").val()
                };
                console.log(get_info);

                //填写内容判断
                if (get_info.price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.actual_price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                }
            }else if($.util.param("tab") == "feast" ){
                get_info = {
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : parseInt($.util.param("product_id")),        
                    'actual_price' : parseInt($("#price").val()),
                    'amount' : $("#amount").val() ,
                    'unit' : "<?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : $("#cost").val(),
                    'update_time' : update_time,
                    'actual_service_ratio' : parseInt($("#fuwufei_input").val()),
                    'remark' : $("#remark").val()
                };
                console.log(get_info);
            }


            //摄影、化妆、灯光定制、视频、平面设计、视频设计、婚纱、婚品、酒水、车辆
            else /*if ($.util.param("tab") == "camera" || $.util.param("tab") == "makeup" || $.util.param("tab") == "made" || $.util.param("tab") == "screen" || $.util.param("tab") == "graphic" || $.util.param("tab") == "film" || $.util.param("tab") == "dress" || $.util.param("tab") == "appliance" || $.util.param("tab") == "drinks" || $.util.param("tab") == "car")*/ {
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : parseInt($.util.param("product_id")),         
                    'actual_price' : parseFloat($("#price").val()),
                    'amount' : $("#amount").val(),
                    'unit' :" <?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : $("#cost").val(),
                    'update_time' : update_time,
                    'actual_service_ratio' : "<?php echo $productData['service_charge_ratio']?>",
                    'remark' : $("#remark").val()
                };

                //填写内容判断
                if (get_info.price == "" || get_info.price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.actual_price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                } else if (isNaN(get_info.amount)) {
                    alert("请输入正确的数量信息！");
                    return false;
                }
            }
            //其他
            /*else if ($.util.param("tab") == "other") {
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    
                    account_id : account_id ,
                    order_id : localStorage.getItem("order_id") ,
                    product_id : $.util.param("product_id"),         
                    actual_price : $("#price").val() ,
                    amount : $("#amount").val(),,
                    unti : <?php echo $productData['unit'] ?>,
                    actual_unit_cost : <?php echo $productData['unit_cost'] ?> ,
                    update_time : <?php echo date('y-m-d h:i:s',time()) ?> ,
                    actual_service_ratio : <?php echo $productData['service_charge_ratio']?>
                }

                //填写内容判断
                if (get_info.price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                }
                ;
            }
            //灯光
            else if ($.util.param("tab") == "lighting") {
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    
                    account_id : account_id ,
                    order_id : localStorage.getItem("order_id") ,
                    product_id : $.util.param("product_id"),         
                    actual_price : $("#price").val() ,
                    amount : $("#amount").val(),,
                    unti : <?php echo $productData['unit'] ?>,
                    actual_unit_cost : <?php echo $productData['unit_cost'] ?> ,
                    update_time : <?php echo date('y-m-d h:i:s',time()) ?> ,
                    actual_service_ratio : <?php echo $productData['service_charge_ratio']?>
                };

                //填写内容判断
                if (get_info.price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                }
                ;
            }*/
            console.log(get_info);
            $.post("<?php echo $this->createUrl('design/updatetp');?>",get_info,function(data){  //bacground data
                
                    alert("提交成功!");
                    if ($.util.param("tab") == "host" || $.util.param("tab") == "video" || $.util.param("tab") == "camera" || $.util.param("tab") == "makeup" || $.util.param("tab") == "other") {
                        location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                    } else if ($.util.param("tab") == "lighting" || $.util.param("tab") == "screen") {
                        location.href = "<?php echo $this->createUrl("design/lightingScreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                    } else if ($.util.param("tab") == "made") {
                        location.href = "<?php echo $this->createUrl("design/lightingMadeDetail", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                    } else if ($.util.param("tab") == "graphic" || $.util.param("tab") == "film") {
                        location.href = "<?php echo $this->createUrl("design/graphicFilm", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                    } else if ($.util.param("tab") == "dress" || $.util.param("tab") == "appliance") {
                        location.href = "<?php echo $this->createUrl("design/dressAppliance", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                    } else if ($.util.param("tab") == "drinks" || $.util.param("tab") == "car") {
                        location.href = "<?php echo $this->createUrl("design/drinksCar", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                    }else if ($.util.param("tab") == "feast") {
                        location.href = "<?php echo $this->createUrl("design/feast", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                    };
                
            });
        });
        
        //删除按钮
        $("#del").on("click", function () {
            var get_info;
            //主持
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            

            if ($.util.param("tab") == "host" ) {
                //赋值 
                
    
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : <?php echo $_GET['product_id']?>,        
                    'actual_price' : parseFloat($("#price").val()),
                    'amount' : 1 ,
                    'unit' : "<?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : $("#cost").val(),
                    'update_time' : update_time,
                    'actual_service_ratio' : "<?php echo $productData['service_charge_ratio']?>"
                };
                console.log(get_info);

                //填写内容判断
                if (get_info.price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.actual_price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                }
            }else if($.util.param("tab") == "feast" ){
                get_info = {
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : <?php echo $_GET['product_id']?>,        
                    'actual_price' : parseInt($("#price").val()),
                    'amount' : $("#amount").val() ,
                    'unit' : "<?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : parseFloat(<?php echo $productData['unit_cost'] ?>) ,
                    'update_time' : update_time,
                    'actual_service_ratio' : parseInt($("#fuwufei_input").val())
                };
                console.log(get_info);
            }


            //摄影、化妆、灯光定制、视频、平面设计、视频设计、婚纱、婚品、酒水、车辆
            else /*if ($.util.param("tab") == "camera" || $.util.param("tab") == "makeup" || $.util.param("tab") == "made" || $.util.param("tab") == "screen" || $.util.param("tab") == "graphic" || $.util.param("tab") == "film" || $.util.param("tab") == "dress" || $.util.param("tab") == "appliance" || $.util.param("tab") == "drinks" || $.util.param("tab") == "car")*/ {
                get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
                    'account_id' : account_id ,
                    'order_id' : <?php echo $_GET['order_id']?> ,
                    'product_id' : <?php echo $_GET['product_id']?>,         
                    'actual_price' : parseFloat($("#price").val()),
                    'amount' : $("#amount").val(),
                    'unit' :" <?php echo $productData['unit'] ?>",
                    'actual_unit_cost' : parseFloat(<?php echo $productData['unit_cost'] ?>) ,
                    'update_time' : update_time,
                    'actual_service_ratio' : "<?php echo $productData['service_charge_ratio']?>"
                };

                //填写内容判断
                if (get_info.price == "" || get_info.price == "") {
                    alert("请补全信息");
                    return false;
                }
                else if (isNaN(get_info.actual_price)) {
                    alert("请输入正确的价格信息！");
                    return false;
                } else if (isNaN(get_info.amount)) {
                    alert("请输入正确的数量信息！");
                    return false;
                }
            }

            $.post("<?php echo $this->createUrl('design/deltp');?>",get_info,function(data){
                //  if(retval.success){
                alert('删除成功');
                if ($.util.param("tab") == "host" || $.util.param("tab") == "video" || $.util.param("tab") == "camera" || $.util.param("tab") == "makeup" || $.util.param("tab") == "other") {
                    location.href = "<?php echo $this->createUrl("design/servicePersonnel", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "lighting" || $.util.param("tab") == "screen") {
                    location.href = "<?php echo $this->createUrl("design/lightingScreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "made") {
                    location.href = "<?php echo $this->createUrl("design/lightingMadeDetail", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "graphic" || $.util.param("tab") == "film") {
                    location.href = "<?php echo $this->createUrl("design/graphicFilm", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "dress" || $.util.param("tab") == "appliance") {
                    location.href = "<?php echo $this->createUrl("design/dressAppliance", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                } else if ($.util.param("tab") == "drinks" || $.util.param("tab") == "car") {
                    location.href = "<?php echo $this->createUrl("design/drinksCar", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                }else if ($.util.param("tab") == "feast") {
                    location.href = "<?php echo $this->createUrl("design/feast", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=<?php echo $_GET['order_id']?>";
                };
                //  }else{
                //    alert("删除失败");
                //  }
                //  }
            });
        });
    })
</script>
</body>
</html>
