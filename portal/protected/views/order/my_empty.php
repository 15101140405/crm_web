<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>订单列表－我的订单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article id="homepage">
    <div class="tool_bar fixed">
        <!-- <div class="l_btn" data-icon="&#xe69c;" id="filter"></div> -->
        <h2 class="page_title" id="pa_title">我的订单</h2>
        <!--管理层显示该title -->
        <?php
        $user_power = '3';//大于2代表管理层
        $arr_hotel = array(//+++++++++++++++++++++++++++所需数据
            '0' => array(
                'hotel_name' => '大郊亭店',
                'staff_hotel_id' => '1',
                'select' => '' //选择的酒店
            ),
            '1' => array(
                'hotel_name' => '航天桥店',
                'staff_hotel_id' => '2',
                'select' => ''
            )
        );
        if ($user_power > 1) {  //显示title
            echo '<h2 class="page_title" id="sel_shop"><select onchange="self.location.href=options[selectedIndex].value">';
            foreach ($arr_hotel as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    $hotel[$key1] = $value1;
                }
                echo "<option " . $hotel['select'] . " value=\"my_order.php?staff_hotel_id=" . $hotel['staff_hotel_id'] . "\" staff_hotel_id=" . $hotel['staff_hotel_id'] . ">" . $hotel['hotel_name'] . "</option>";
            }
            echo '</select></h2>';
        }
        ?>
        <!-- <div class="r_btn" data-icon="&#xe767;"></div> -->
    </div>
    <?php
    if ($user_power > 1) {
        ?>
        <!-- 策划师 和 管理层 显示该tab -->
        <div class="tab_module fixed2" id="top_tab">
            <p class="tab_btn act" id="total">
                <span>全部</span>
            </p>
            <p class="tab_btn" id="wed">
                <span>婚礼</span>
            </p>
            <p class="tab_btn" id="meeting">
                <span>会议</span>
            </p>
        </div>
    <?php } ?>
    <!-- <div class="tab_module" id="second_tab">
      <p class="tab_btn act" id="second_total">
          <span>全部</span>
      </p>
      <p class="tab_btn" id="non_front">
          <span>未交订金</span>
      </p>
      <p class="tab_btn" id="front">
          <span>已交订金</span>
      </p>
      <p class="tab_btn" id="middle">
          <span>付中期款</span>
      </p>
      <p class="tab_btn" id="end">
          <span>已完成</span>
      </p>
    </div> -->
    <div class="ulist_module" style='position: relative;top: 120px;'>
        <ul class="ulist order_list">
            还没有属于您的订单！
        </ul>
    </div>
</article>
<!-- <article id="filter" class="hid">
  <div class="tool_bar">
    <div class="l_btn" data-icon="&#xe679;"></div>
    <h2 class="page_title">筛选</h2>
    <div class="r_btn">确定</div>
  </div>
  <div class="select_ulist_module">
    <ul class="select_ulist">
      <li class="select_ulist_item list_more slide_btn">月份</li>
    </ul>
</article> -->
<article  class="slide_page hid" id="filter_month">
  <div class="tool_bar">
    <div class="l_btn" data-icon="&#xe679;" id="back"></div>
    <h2 class="page_title">筛选</h2>
    <!-- 筛选按钮 用户类型为“收银”时，隐藏该按钮 -->
    <div class="r_btn" id="ok">确定</div>
  </div>
  <div class="select_ulist_module">
    <ul class="select_ulist" id="month">
      <!-- <li class="select_ulist_item check_box check_box_ckd">1月</li>   -->
      <li class="select_ulist_item check_box">1月</li>
      <li class="select_ulist_item check_box">2月</li>
      <li class="select_ulist_item check_box">3月</li>
      <li class="select_ulist_item check_box">4月</li>
      <li class="select_ulist_item check_box">5月</li>
      <li class="select_ulist_item check_box">6月</li>
      <li class="select_ulist_item check_box">7月</li>
      <li class="select_ulist_item check_box">8月</li>
      <li class="select_ulist_item check_box">9月</li>
      <li class="select_ulist_item check_box">10月</li>
      <li class="select_ulist_item check_box">11月</li>
      <li class="select_ulist_item check_box">12月</li>
    </ul>
  </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script>

    $(function () {

        

        //点击变换导航选中状态
        $(".tab_btn").on("click", function () {
            $(this).addClass("act");
            $(this).siblings().removeClass("act");
        });

        //点击增加订单
        $(".r_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/index")?>&from=my&code=";
        });

        //判断角色，隐藏或显示选择店面
        select_shop();
        function select_shop(){
            var staff_type = "策划师";
            if(staff_type == "管理层" || staff_type == "财务"){
                $("#pa_title").addClass("hid");
            }else if(staff_type == "店长" || staff_type == "策划师" || staff_type == "统筹师"){
                $("#sel_shop").addClass("hid");
            }
        }
        
        

    

    })

</script>
</body>
</html>