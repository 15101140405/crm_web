<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<!-- 所有的子项目报价单订单填写都是此模板 -->

<!-- 成本项增加也是此模板 -->

<title>订单填写</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<article>
  <div class="tool_bar">
    <div class="l_btn" data-icon="&#xe679;"></div>
    <h2 class="page_title">订单填写</h2>
  </div>
  <div class="ulist_module pad_b50">
        <ul class="ulist">
            <!-- <li class="ulist_item flex">
                折扣价
                <div class="flex1">
                    <input class="align_r t_green"  value="<?php echo $orderproduct['actual_price']; ?>" type="text" placeholder="<?php  echo $productData['unit_price'];?>" id="price"/>
                </div>
                <i class="mar_l10 t_green">元/桌</i>
            </li>
            <li class="ulist_item flex">
                数量
                <div class="flex1">
                    <input class="align_r t_green" type="text" value="<?php echo $orderproduct['unit']; ?>" placeholder="请输入桌数" id="amount"/>
                </div>
                <i class="mar_l10 t_green"><?php  echo $productData['unit'];?></i>
            </li>
            <li class="ulist_item flex" id='fuwufei'>
                服务费
                <div class="flex1">
                    <input class="align_r t_green" type="text" value="<?php echo $orderproduct['actual_service_ratio']; ?>" placeholder="<?php  echo $productData['service_charge_ratio'];?>" id="fee"/>
                </div>
            </li> -->
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
        <?php
            }
        ?>
        </ul>
    </div>
    <!-- 页面元素太多时,上面元素要加class pad_b50, 否则会有遮罩部分看不到-->
    <div class="bottom_fixed_bar">
        <div class="r_btn" id="add">确定</div>
        <div class="r_btn" id="update">提交订单</div>
        <div class="r_btn" id="del" style="background-color:red;">删除</div>
    </div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
$(function(){

    //页面初始化
    if($.util.param("type") == "edit"){
        $("#add").remove();
        //此处用php从后端取数
    }else if($.util.param("type") == "new"){
        $("#del").remove();
        $("#update").remove();
    };
    if($.util.param("tab") == 'changdifei' || $.util.param("tab") == 'lighting' || $.util.param("tab") == 'screen' ){
        $("#fuwufei").remove();
    }


    //返回
    $(".l_btn").on("click",function(){
        location.href = 'wed&meeting_feast.php?from=' + $.util.param("from") + '&type=' + $.util.param("type") ;
    });

    //点击确定按钮，并将表单信息提交给后台
    $("#add").on("click",function(){
        var myDate = new Date();

        var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();

        get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
            'account_id' : <?php echo $_SESSION['account_id'];?>,
            'order_id' : <?php echo $_GET['order_id'] ;?> ,
            'product_id' : parseInt($.util.param("product_id")),         
            'actual_price' : parseInt($("#price").val()),
            'amount' : $("#amount").val(),
            'unit' :" <?php echo $productData['unit'] ?>",
            'actual_unit_cost' : $("#cost").val(),
            'update_time' : update_time,
            'actual_service_ratio' : <?php echo $productData['service_charge_ratio']?>
        };
        /*alert(get_info.amount);*/

        //填写内容判断
        if (get_info.actual_price == "" || get_info.unit == "" || get_info.amount == "") {
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
        console.log(get_info);

        $.post("<?php echo $this->createUrl('meeting/savetp');?>",get_info,function(data){  //bacground data
            alert("提交成功!");
            if($.util.param("tab") == "feast"){
                location.href = "<?php echo $this->createUrl("meeting/feast", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');    
            }else if($.util.param("tab") == "changdifei"){
                location.href = "<?php echo $this->createUrl("meeting/changdifei", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }else if($.util.param("tab") == "lighting"){
                location.href = "<?php echo $this->createUrl("meeting/lightingscreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }else if($.util.param("tab") == "screen"){
                location.href = "<?php echo $this->createUrl("meeting/lightingscreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }
            
        });
    });

    //编辑
    $("#update").on("click", function () {
        var get_info;

        var myDate = new Date();

        var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
    
        get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
            'account_id' : <?php echo $_SESSION['account_id'];?>,
            'order_id' : <?php echo $_GET['order_id'] ;?> ,
            'product_id' : parseInt($.util.param("product_id")),         
            'actual_price' : parseFloat($("#price").val()),
            'amount' : $("#amount").val(),
            'unit' :" <?php echo $productData['unit'] ?>",
            'actual_unit_cost' : $("#cost").val(),
            'update_time' : update_time,
            'actual_service_ratio' : <?php echo $productData['service_charge_ratio']?>
        };
        /*console.log(get_info);*/
        $.post("<?php echo $this->createUrl('meeting/updatetp');?>",get_info,function(data){  //bacground data    
            alert("提交成功!");
            if($.util.param("tab") == "feast"){
                location.href = "<?php echo $this->createUrl("meeting/feast", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');    
            }else if($.util.param("tab") == "changdifei"){
                location.href = "<?php echo $this->createUrl("meeting/changdifei", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }else if($.util.param("tab") == "lighting"){
                location.href = "<?php echo $this->createUrl("meeting/changdifei", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }else if($.util.param("tab") == "screen"){
                location.href = "<?php echo $this->createUrl("meeting/lightingscreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }
        });
        
    });

    //删除按钮
    $("#del").on("click", function () {
        var get_info;
        
        var myDate = new Date();

        var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();

        get_info = {//因为wedding_linkman_info.html页面，无法从后端获取order_id，所以lacalStorage.getItem("order_id")未定义，所以暂时注释掉
            'account_id' : <?php echo $_SESSION['account_id'];?>,
            'order_id' : <?php echo $_GET['order_id'] ;?> ,
            'product_id' : parseInt($.util.param("product_id")),         
            'actual_price' : parseFloat($("#price").val()),
            'amount' : $("#amount").val(),
            'unit' :" <?php echo $productData['unit'] ?>",
            'actual_unit_cost' : $("#cost").val(),
            'update_time' : update_time,
            'actual_service_ratio' : <?php echo $productData['service_charge_ratio']?>
        };

        $.post("<?php echo $this->createUrl('meeting/deltp');?>",get_info,function(data){
            alert('删除成功');
            if($.util.param("tab") == "feast"){
                location.href = "<?php echo $this->createUrl("meeting/feast", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');    
            }else if($.util.param("tab") == "changdifei"){
                location.href = "<?php echo $this->createUrl("meeting/changdifei", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }else if($.util.param("tab") == "lighting"){
                location.href = "<?php echo $this->createUrl("meeting/changdifei", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }else if($.util.param("tab") == "screen"){
                location.href = "<?php echo $this->createUrl("meeting/lightingscreen", array());?>&tab=" + $.util.param("tab") + "&from=" + $.util.param("from") + "&order_id=" + localStorage.getItem('order_id');
            }
        });
    });

  /* ===========================
   * 获取界面填写的供应商信息
   * =========================== */
   //后台自动生成feast_id
    function get_feast_info(){
    var f_info = {
          price : $("#price").val(),
          number : $("#number").val(),
          fee : $("#fee").val(),
          remark : $("#remark").val(),
          order_id : localStorage.getItem("order_id")
        }

        return f_info;
    }


})
</script>
</body>
</html>