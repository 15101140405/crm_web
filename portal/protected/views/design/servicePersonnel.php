<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>服务人员</title>
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
        <h2 class="page_title">服务人员</h2>
    </div>
    <!-- 所有的子项目报价单都是此模板 -->
    <div class="order_abstract mar_b10">

        <?php
        /*$serve_bill = array(  //background data
            'total' => '2322',
            'desc' => array(
                '主持人' => '10002',
                '摄影师' => '10002',
                '化妆师1' => '100',
                '化妆师' => '1002',
                '督导师' => '1602',
                '灯光师' => '1802'
            )

        );*/
        ?>

        <p class="title">总价 &yen;<?php echo $serve_bill['total']; ?></p>
        <div class="desc">
            <?php
            foreach ($serve_bill['desc'] as $key => $value) {
                echo "<p>$key  &yen;$value</p>";
            }

            ?>
        </div>
    </div>
    <?php
    $arr_category_type = array(
        '主持' => 'host', //主持
        '摄像' => 'video', //摄像
        '摄影' => 'camera',//摄影
        '化妆' => 'makeup',//化妆
        '其他' => 'other' //其他
    );

    ?>
    <!-- 为了全站ui统一, 这里的tab样式我给换了 -->
    <div class="tab_module">
        <?php
        foreach ($arr_category_type as $key => $value) {
            if ($value == 'host') {
                $act = 'act';
            } else {
                $act = '';
            }
            ?>
            <p class="tab_btn <?php echo $act; ?>" type-id="<?php echo $value; ?>">
                <span><?php echo $key; ?></span>
            </p>
        <?php } ?>
    </div>
    <div class="ulist_module">
        <ul class="ulist charge_list">
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
        //从tp_detail返回，渲染对应tab页面
        if ($.util.param("tab") != "") {
            showdata($.util.param("tab"));
            $("[type-id='" + $.util.param("tab") + "']").addClass("act");
            $("[type-id='" + $.util.param("tab") + "']").siblings().removeClass("act");
        } else {
            showdata("host");
        }

        //点击导航条，渲染对应内容
        $(".tab_btn").on("click", function () {
            //改变按钮状态
            $(this).addClass("act");
            $(this).siblings().removeClass("act");
            showdata($(this).attr("type-id"));
        })

        //点击返回按钮，判断from，返回对应页面
        $(".l_btn").on("click", function () {
            if ($.util.param("from") == "detail") {
                location.href = "<?php echo $this->createUrl("design/detail");?>&order_id=" + $.util.param("order_id");
            } else {
                location.href = "<?php echo $this->createUrl("design/bill", array());?>&order_id=" + $.util.param("order_id");
            }
        });

        //渲染对应内容
        function showdata(data) {
            var type_id = data;
            switch (type_id) {
                case "host":
                    $(".charge_list").empty(); //清空订单列表
                <?php
                /*$arr_category_host = array(//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => '吴辉',
                        'unit' => '元/场'

                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '小柯',
                        'unit' => '元/场'

                    )

                );*/
                foreach ($arr_category_host as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_host[$key1] = $value1;
                }
                ?>
                    var html_host
                    html_host = '<li class="ulist_item list_more " host-id="<?php  echo $arr_host['product_id'];?>">';//id由php从后端取数，格式为host＋序号；
                    html_host += '<div class="item">';
                    html_host += '<p class="name"><?php  echo $arr_host['name'];?></p>';
                    html_host += '</div>';
                    html_host += '<div>';
                    html_host += '</li>';

                    $(".charge_list").prepend(html_host); //打印新的订单列表
                <?php   }   ?>

                    //先判断是否已经选择主持人

                <?php
                foreach ($host_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var host_id = "<?php echo $data; ?>";   //php从后端取得“已经选择的主持人，的id”，此处暂时做个示例
                    if (host_id != "") {
                        $("[host-id='" + host_id + "']").removeClass("list_more");
                        $("[host-id='" + host_id + "']").addClass("selected");

                    };
                <?php 
                }
                ?>
                    

                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("host-id") + "&type=edit&tab=host&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("host-id") + "&type=new&tab=host&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "video":
                    $(".charge_list").empty(); //清空订单列表
                <?php
                /*$arr_category_video = array( //video//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => '高清',
                        'supplier_name' => '小李zi'
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '5D',
                        'supplier_name' => '小李zi'
                    )

                );*/
                foreach ($arr_category_video as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_video[$key1] = $value1;
                }
                ?>
                    var html_video;
                    html_video = '<li class="ulist_item list_more " video-id="<?php  echo $arr_video['product_id'];?>">';
                    html_video += '<div class="item ">';
                    html_video += '<p class="name"><?php  echo $arr_video['name'];?></p>';
                    html_video += '</div><i class="name"><?php  echo $arr_video['supplier_name'];?></i>';//!$  此处加入了供应商姓名
                    html_video += '</li>';
                    $(".charge_list").prepend(html_video); //打印新的订单列表
                <?php } ?>
                    //先判断是否已经选择摄像                

                <?php
                foreach ($video_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var video_id = "<?php echo $data; ?>";   //php从后端取得此数，此处暂时做个示例
                    if (video_id != "") {
                        $("[video-id='" + video_id + "']").removeClass("list_more");
                        $("[video-id='" + video_id + "']").addClass("selected");
                    };
                <?php 
                }
                ?>

                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("video-id") + "&type=edit&tab=video&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("video-id") + "&type=new&tab=video&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "camera":
                    $(".charge_list").empty(); //清空订单列表
                <?php

                /*$arr_category_camera = array(//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => 'B级',
                        'supplier_name' => '小李'
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => 'A级3',
                        'supplier_name' => '小李3'
                    )

                );*/
                foreach ($arr_category_camera as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_camera[$key1] = $value1;
                }
                ?>
                    var html_camera;
                    html_camera = '<li class="ulist_item list_more " camera-id="<?php  echo $arr_camera['product_id'];?>">';
                    html_camera += '<div class="item ">';
                    html_camera += '<p class="name"><?php  echo $arr_camera['name'];?></p>';
                    html_camera += '</div><i class="name"><?php  echo $arr_camera['supplier_name'];?></i>';
                    html_camera += '</li>';

                    $(".charge_list").prepend(html_camera); //打印新的订单列表
                <?php } ?>
                    //先判断是否已经选择摄影
                <?php
                foreach ($camera_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var camera_id = "<?php echo $data; ?>";   //php从后端取得此数，此处暂时做个示例
                    if (camera_id != "") {
                        $("[camera-id='" + camera_id + "']").removeClass("list_more");
                        $("[camera-id='" + camera_id + "']").addClass("selected");
                    };
                <?php
                }
                ?>
                    

                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("camera-id") + "&type=edit&tab=camera&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("camera-id") + "&type=new&tab=camera&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "makeup":
                    $(".charge_list").empty(); //清空订单列表
                <?php

                /*$arr_category_makeup = array(//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => 'B级',
                        'supplier_name' => '小李4'

                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => 'A级4',
                        'supplier_name' => '小李'
                    )

                );*/
                foreach ($arr_category_makeup as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_makeup[$key1] = $value1;
                }
                ?>
                    var html_makeup;
                    html_makeup = '<li class="ulist_item list_more " makeup-id="<?php  echo $arr_makeup['product_id'];?>">';
                    html_makeup += '<div class="item ">';
                    html_makeup += '<p class="name"><?php  echo $arr_makeup['name'];?></p>';
                    html_makeup += '</div><i class="name"><?php  echo $arr_makeup['supplier_name'];?></i>';   //!$ 此处去掉了价格，加入了供应商姓名
                    html_camera += '</li>';

                    $(".charge_list").prepend(html_makeup); //打印新的订单列表
                <?php  } ?>
                    //先判断是否已经选择摄影
                <?php
                foreach ($makeup_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var makeup_id = "<?php echo $data; ?>";   //php从后端取得此数，此处暂时做个示例
                    if (makeup_id != "") {
                        $("[makeup-id='" + makeup_id + "']").removeClass("list_more");
                        $("[makeup-id='" + makeup_id + "']").addClass("selected");
                    };
                <?php
                }
                ?>
                    

                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("makeup-id") + "&type=edit&tab=makeup&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("makeup-id") + "&type=new&tab=makeup&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;

                case "other":
                    $(".charge_list").empty(); //清空订单列表
                <?php

                /*$arr_category_makeup = array(//background data
                    '0' => array(
                        'product_id' => '2',
                        'name' => '督导师',
                        'supplier_name' => '小李'
                    ),
                    '1' => array(
                        'product_id' => '1',
                        'name' => '灯光师',
                        'supplier_name' => '小李'

                    )

                );*/
                foreach ($arr_category_other as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $arr_makeup[$key1] = $value1;
                }
                ?>
                    var html_other;
                    html_other = '<li class="ulist_item list_more" other-id="<?php  echo $arr_makeup['product_id'];?>">';
                    html_other += '<div class="item ">';
                    html_other += '<p class="name"><?php  echo $arr_makeup['name'];?></p>';
                    html_other += '</div><i class="name"><?php  echo $arr_makeup['supplier_name'];?></i>'; //!$ 此处加入了供应商姓名
                    html_other += '</li>';
                    $(".charge_list").prepend(html_other); //打印新的订单列表
                <?php  } ?>
                    //先判断是否已经选择摄影
                <?php
                foreach ($other_data as $key => $value) {
                    $data=$value['product_id'];
                ?>
                    var other_id = "<?php echo $data; ?>";   //php从后端取得此数，此处暂时做个示例
                    if (other_id != "") {
                        $("[other-id='" + other_id + "']").removeClass("list_more");
                        $("[other-id='" + other_id + "']").addClass("selected");
                    };
                <?php
                }
                ?>
                    

                    //点击li跳转子页(渲染后界面)
                    $("li.selected").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("other-id") + "&type=edit&tab=other&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    $("li.list_more").on("click", function () {
                        location.href = "<?php echo $this->createUrl("design/tpDetail", array());?>&product_id=" + $(this).attr("other-id") + "&type=new&tab=other&from=" + $.util.param("from") + "&order_id=" + $.util.param("order_id");
                    })
                    break;
            }
        }
    })
</script>
</body>
</html>
