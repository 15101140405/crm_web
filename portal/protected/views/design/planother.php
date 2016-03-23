<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>策划费&杂费</title>
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
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">策划费&杂费</h2>
    </div>
    <!-- 所有的子项目报价单都是此模板 -->
    <div class="order_abstract mar_b10">
        <?php
        /*$drinks_bill = array(
            'total' => '2354',
            'desc' => array(
                '酒水1' => '1033',
                '车辆' => '10022'
            )

        );*/
        ?>
        <p class="title">总价 &yen;<?php echo $planother_bill['total']; ?></p>
    </div>
    <div class="ulist_module">
        <ul class="ulist charge_list" id="product">
            <!-- 选中的时候加class -->
            <!-- <li class="ulist_item selected">
                <div class="item">
                    <p class="name">套餐A</p>
                </div>
                <div>
                    <p class="price">&yen;2888/场</p>
                </div>
            </li>
            <li class="ulist_item list_more">
                <div class="item">
                    <p class="name">套餐B</p>
                </div>
                <div>
                    <p class="price">&yen;2888/场</p>
                </div>
            </li> -->
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {

        showdata();


        //点击返回按钮，判断from，返回对应页面
        $(".l_btn").on("click", function () {
            if ($.util.param("from") == "detail") {
                location.href = "<?php echo $this->createUrl("design/detail");?>";
            } else {
                location.href = "<?php echo $this->createUrl("design/bill", array());?>";
            }
        });

        //渲染对应内容
        function showdata() {
            
            var html_drinks = '';
            <?php
            foreach ($arr_category_planother as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $arr_planother[$key1] = $value1;
            };
            ?>
                
                html_drinks += '<li class="ulist_item list_more " planother-id="<?php echo $arr_planother['id'];?>">';//id由php从后端取数，格式为lighting＋序号；
                html_drinks += '<div class="item">';
                html_drinks += '<p class="name"><?php echo $arr_planother['name'];?></p>';
                html_drinks += '</div><i class="name"><?php echo $arr_planother['supplier_name'];?></i>';   //!$ 此处加入了供应商姓名
                html_drinks += '</li>';
            <?php
            }
            ?>
                $("#product").empty(); //清空订单列表
                $("#product").prepend(html_drinks); //打印新的订单列表

                //先判断是否已经选择平面设计
            <?php
            foreach ($planother_data as $key => $value) {
                $data=$value['product_id'];
            ?>
                var planother_id =  "<?php echo $data; ?>"; 
                
                if (planother_id != "") {
                    $("[planother-id='" + planother_id + "']").removeClass("list_more");
                    $("[planother-id='" + planother_id + "']").addClass("selected");

                }
                ;
            <?php
            }
            ?>
                //点击li跳转子页(渲染后界面)
                $("#product li.selected").on("click", function () {
                    location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("planother-id") + "&type=edit&tab=planother&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                })
                $("#product li.list_more").on("click", function () {
                    location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("planother-id") + "&type=new&tab=planother&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                })
        }



    })
</script>
</body>
</html>
