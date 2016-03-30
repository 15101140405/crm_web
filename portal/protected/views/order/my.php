<?php
error_reporting(E_ALL & ~E_NOTICE);
function order_status(&$result)
{   //根据状态确定颜色
    switch ($result["order_status"]) {
        case 0:
            $result['order_color'] = "status_red";
            $result['order_stat'] = "新订单";
            break;
        case 1:
            $result['order_color'] = "status_white";
            $result['order_stat'] = "未交订金";
            break;
        case 2:
            $result['order_color'] = "status_green";
            $result['order_stat'] = "已交订金";
            break;
        case 3:
            $result['order_color'] = "status_orange";
            $result['order_stat'] = "付中期款";
            break;
        case 4:
            $result['order_color'] = "status_darkgray";
            $result['order_stat'] = "已完成";
            break;
        default:
            $result['order_color'] = "status_white";
            $result['order_stat'] = "未交订金";
            break;
    }
echo $userId;
}


//分离数据

//全部
foreach ($arr_order as $key => $value) {
    foreach ($value as $key1 => $value1) {
        $my_order[$key1] = $value1;

    }
    order_status($my_order); //订单状态
    if ($my_order['order_type'] == '1') {//全部会议数据
        $my_order_meeting[] = $my_order;
        if (empty($my_order_meeting)) {
            $result = array('0' => array(
                'id' => '',
                'order_date' => '',
                'order_type' => '',
                'order_category' => '',
                'order_name' => '',
                'account_id' => '',
                'order_status' => '',
                'order_new' => ''
            )
            );
        }
    }
    if ($my_order['order_type'] == '2') {//全部婚礼数据
        $my_order_wedding[] = $my_order;
        if (empty($my_order_wedding)) {
            $result = array('0' => array(
                'id' => '',
                'order_date' => '',
                'order_type' => '',
                'order_category' => '',
                'order_name' => '',
                'account_id' => '',
                'order_status' => '',
                'order_new' => ''
            )
            );
        }
    }
    $my_order_total[] = $my_order;
}
//print_r($my_order_total);
//print_r($my_order_meeting);
//print_r($my_order_wedding);
function order_stat($arr, $stat)
{ //$arr为二位数组，$stat为状态.循环出谋状态订单
    $result = '';
    foreach ($arr as $key => $value) {
        if ($value['order_status'] == $stat) {
            $stat_exist = true;
        } else {
            $stat_exist = false;
        }

        if ($stat_exist) {
            $result[] = $value;
        }

    }
    if (empty($result)) {
        $result = array(
            '0' => array(
                'id' => '',
                'order_date' => '',
                'order_type' => '',
                'order_category' => '',
                'order_name' => '',
                'account_id' => '',
                'order_status' => '',
                'order_new' => ''
            )
        );
    }
    return $result;
}

function shuchu_html($result,$order_status)
{
    if($order_status == 0 || $order_status == 1){
        $html = "<li class=\"ulist_item swipeout\"  order-type=\"" . $result['order_type'] . "\" order-id=\"" . $result['id'] . "\">" . "<div class=\"item-content\">" .
        /*"<span class=\"order_status \" style=\"margin-top: 15px;margin-bottom: 15;font-size:1.3rem;\" order_status=\"". $result['order_status'] ."\"></span>"*/ 
        "<p  style=\"margin-left: 20px;\" ><input order_status=\"". $result['order_status'] ."\" order-id=\"" . $result['id'] . "\" id=\"switch\" type=\"checkbox\" name=\"check-1\" value=\"4\" class=\"lcs_check\" autocomplete=\"off\" /></p>".
        /*"<span class=\"order_status " . $result['order_color'] . "\">" . $result['order_stat'] . "</span>" .*/
        "<div class=\"order_info\">" .
        "<p class=\"customer\" style=\"margin-top:20px;margin-bottom:10px;font-size: 1.3rem;line-height: 0rem;\">" . $result['order_name'] . "</i></p>" .
       /* "<p class=\"customer\"><i class=" . $result['order_new'] . ">" . $result['account_id'] . "</i></p>" .*/
        "<p class=\"desc\" style=\"margin-top: 8px;margin-bottom: 0;font-size:1rem;\" >" . $result['order_date'] . "</p>" .
        /*"<p class=\"desc\" style＝'color:#fff; display:none'> ". $result['order_time'] . "</p>" .*/
        "</div>" ."<div class=\"swipeout-actions-right\">" . "<a class=\"swipeout-delete del\">删除订单</a>" . "</div></div>" .
        "</li>";  //更多行，依次复制即可
    }else{
        $html = "<li class=\"ulist_item swipeout\" order-id=\"" . $result['id'] . "\" order-type=\"" . $result['order_type'] . "\">" . "<div class=\"item-content\">" .
        "<span class=\"order_status \" style=\"margin-top: 15px;margin-bottom: 15;font-size:1.3rem;\" order_status=\"". $result['order_status'] ."\"></span>". 
        /*'<p><input id="switch" type="checkbox" name="check-1" value="4" class="lcs_check" autocomplete="off" /></p>'*/
        /*"<span class=\"order_status " . $result['order_color'] . "\">" . $result['order_stat'] . "</span>" .*/
        "<div class=\"order_info\">" .
        "<p class=\"customer\" style=\"margin-top:20px;margin-bottom:10px;font-size: 1.3rem;line-height: 0rem;\">" . $result['order_name'] . "</i></p>" .
       /* "<p class=\"customer\"><i class=" . $result['order_new'] . ">" . $result['account_id'] . "</i></p>" .*/
        "<p class=\"desc\" style=\"margin-top: 8px;margin-bottom: 0;font-size:1rem;\" >" . $result['order_date'] . "</p>" .
        /*"<p class=\"desc\" style＝'color:#fff; display:none'> ". $result['order_time'] . "</p>" .*/
        "</div>" ."<div class=\"swipeout-actions-right\">" . "<a class=\"swipeout-delete del\">删除订单</a>" . "</div></div>" .
        "</li>";  //更多行，依次复制即可
    }
    
    return $html;
}


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
    <link rel="stylesheet" href="css/framework7.ios.min.css">
    <link rel="stylesheet" href="css/framework7.ios.colors.min.css">
    <!-- <link rel="stylesheet" href="css/framework7.material.min.css"> -->
    <link rel="stylesheet" href="css/framework7.material.colors.min.css">
    <link rel="stylesheet" href="css/upscroller.css">
    <link rel="stylesheet" href="css/my-app.css">
    <link rel="stylesheet" href="css/lc_switch.css">
<style type="text/css">
body * {
  font-family: Arial, Helvetica, sans-serif;
  box-sizing: border-box;
  -moz-box-sizing: border-box;
}
h1 {
  margin-bottom: 10px;
  padding-left: 35px;
}
a {
  color: #888;
  text-decoration: none;
}
small {
  font-size: 13px;
  font-weight: normal;
  padding-left: 10px;
}
#first_div {
  width: 90%;
  max-width: 600px;
  min-width: 340px;
  margin: 50px auto 0;
  color: #444;
}
#second_div {
  width: 90%;
  max-width: 600px;
  min-width: 340px;
  margin: 50px auto 0;
  background: #f3f3f3;
  border: 6px solid #eaeaea;
  padding: 20px 40px 40px;
  text-align: center;
  border-radius: 2px;
}
#third_div {
  width: 90%;
  max-width: 600px;
  min-width: 340px;
  margin: 10px auto 0;
}
</style>
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
            '1' => array(
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

    <div class="ulist_module list-block" style='position: relative;top: 100px;'>
        <ul class="ulist order_list">
            <?php //全部订单
            $html = '';
            foreach ($my_order_total as $key => $value) {
                $html .= shuchu_html($value,$value['order_status']);
            }

            echo $html;

            ?>
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
<!-- <article  class="slide_page hid" id="filter_month">
  <div class="tool_bar">
    <div class="l_btn" data-icon="&#xe679;" id="back"></div>
    <h2 class="page_title">筛选</h2>
     筛选按钮 用户类型为“收银”时，隐藏该按钮 -->
    <!-- <div class="r_btn" id="ok">确定</div>
  </div>
  <div class="select_ulist_module">
    <ul class="select_ulist" id="month">
       <li class="select_ulist_item check_box check_box_ckd">1月</li>   -->
      <!-- <li class="select_ulist_item check_box">1月</li>
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
</article>  --> 
<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script src="js/jquery.js"></script>
<script src="js/lc_switch.js" type="text/javascript"></script>


<script type="text/javascript" src="js/framework7.min.js"></script>
<script type="text/javascript" src="js/upscroller.js"></script>
<script type="text/javascript" src="js/my-app.js"></script>
<script>

    $(function () {

        //删除订单
        $(".del").on("click",function(){
            $("li").unbind("click");
            var choice=confirm("您确认要删除吗？订单删除将无法恢复！", function() { }, null);
            if(choice){
                order_id = $(this).parent().parent().parent().attr('order-id');
                //alert(order_id);
                $.post("<?php echo $this->createUrl('order/delorder');?>",{'order_id':order_id},function(retval){
                    if(retval == "success"){
                        alert('删除成功！');
                        location.href = "<?php echo $this->createUrl('order/my');?>&code=&t=plan";//刷新当前页面.    
                    }else{
                        alert('删除失败！');
                    }
                    
                });
            };
        });

        

        //点击变换导航选中状态
        $(".tab_btn").on("click", function () {
            $(this).addClass("act");
            $(this).siblings().removeClass("act");
        });

        //点击增加订单
        $(".r_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("order/index", array());?>&from=my";
        });

        //判断角色，隐藏或显示选择店面
        select_shop();
        buttonclick_xuanran();
        function select_shop(){
            var staff_type = "策划师";
            if(staff_type == "管理层" || staff_type == "财务"){
                $("#pa_title").addClass("hid");
            }else if(staff_type == "店长" || staff_type == "策划师" || staff_type == "统筹师"){
                $("#sel_shop").addClass("hid");
            }
        };
        
        /*筛选 开始*/
        //点击筛选按钮
        $('#filter').on('click',function(){
            $('.slide_page').removeClass("hid");
            $('.slide_page').addClass('show');
          });

        //选择月份
        select_month();
        function select_month(){
            //月份多选框，点击事件
            $("#month li").on("click",function(){
                if($(this).hasClass("check_box_ckd")){
                    $(this).removeClass("check_box_ckd");
                }else{
                    $(this).addClass("check_box_ckd");
                }
            })

            //返回点击事件
            $("#back").on("click",function(){
                $('.slide_page').addClass('hid');
                $('.slide_page').removeClass('show');
                $("#month li").removeClass("check_box_ckd");
            })
        };
        /*筛选 结束*/






        //判断  统筹／策划
        if("<?php echo $_GET['t'];?>" == "plan"){
            order_status_xuanran ();
            buttonclick_xuanran();
            $(".ulist_item").on("click", function () {
                    //判断order－type，进入不同页面
                    var order_status = $(this).find("span").attr("order_status");
                    var order_type = $(this).attr("order-type");
                    var order_id = $(this).attr("order-id");
                    plan_jump (order_type,order_status,order_id);
                });
            //选中"全部"订单，打印全部订单
            $("#total").on("click", function () {
                var html;
                <?php
                $html = '';
                foreach ($my_order_total as $key => $value) {
                    $html .= shuchu_html($value,$value['order_status']);
                }
                echo "html ='" . $html . "';";
                ?>
                $("#second_tab").remove();
                $(".order_list").empty(); //清空订单列表
                $(".order_list").prepend(html); //打印新的订单列表
                order_status_xuanran ();
                buttonclick_xuanran();
                alert(1);
                $(".ulist_item").on("click", function () {
                    //判断order－type，进入不同页面
                    var order_status = $(this).find("span").attr("order_status");
                    var order_type = $(this).attr("order-type");
                    var order_id = $(this).attr("order-id");
                    plan_jump (order_type,order_status,order_id);
                });
            })

            //选中"婚礼"，出现订单状态筛选条
            $("#wed").on("click", function () {
                //打印订单状态筛选条
                var html_tab = '<div class="tab_module fixed3" id="second_tab"><p class="tab_btn act" id="second_total"><span>全部</span></p><p class="tab_btn" id="non_front"><span>预定</span></p><p class="tab_btn" id="front"><span>已订</span></p><p class="tab_btn" id="middle"><span>付中期</span></p><p class="tab_btn" id="end"><span>已完成</span></p></div>'
                $("#second_tab").remove();
                $("#top_tab").after(html_tab);
                $(".ulist_item").removeClass("hid");
                $("[order-type='1']").addClass("hid");
                $("[order-type='2']").removeClass("hid");
                order_status_xuanran ();
                buttonclick_xuanran();
                $(".ulist_item").on("click", function () {
                    //判断order－type，进入不同页面
                    var order_status = $(this).find("span").attr("order_status");
                    var order_type = $(this).attr("order-type");
                    var order_id = $(this).attr("order-id");
                    plan_jump (order_type,order_status,order_id);
                });

                //点击变换导航选中状态
                $(".tab_btn").on("click", function () {
                    $(this).addClass("act");
                    $(this).siblings().removeClass("act");
                });

                //选中“婚礼”－“全部”
                $("#second_total").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })

                //选中“婚礼”－“未交订金”
                $("#non_front").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    $("[order-type='2']").find("[order_status='0']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='2']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='3']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='4']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });
                })

                //选中“婚礼”－“已交订金”
                $("#front").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    $("[order-type='2']").find("[order_status='0']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='1']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='3']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='4']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })

                //选中“婚礼”－“已交中期款”
                $("#middle").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    $("[order-type='2']").find("[order_status='0']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='2']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='1']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='4']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })

                //选中“婚礼”－“已完成”
                $("#end").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    $("[order-type='2']").find("[order_status='0']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='2']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='3']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='1']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })
            });

            //选中"会议"，出现订单状态筛选条
            $("#meeting").on("click", function(){
                //打印订单状态筛选条
                var html_tab = '<div class="tab_module fixed3" id="second_tab"><p class="tab_btn act" id="meeting_total"><span>全部</span></p><p class="tab_btn" id="meeting_non_front"><span>未交订金</span></p><p class="tab_btn" id="meeting_front"><span>已交订金</span></p><p class="tab_btn" id="meeting_end"><span>已完成</span></p></div>'
                $("#second_tab").remove();
                $("#top_tab").after(html_tab);
                $(".ulist_item").removeClass("hid");
                $("[order-type='2']").addClass("hid");
                $("[order-type='1']").removeClass("hid");
                order_status_xuanran ();
                buttonclick_xuanran();
                $(".ulist_item").on("click", function () {
                    //判断order－type，进入不同页面
                    var order_status = $(this).find("span").attr("order_status");
                    var order_type = $(this).attr("order-type");
                    var order_id = $(this).attr("order-id");
                    plan_jump (order_type,order_status,order_id);
                });

                //点击变换导航选中状态
                $(".tab_btn").on("click", function () {
                    $(this).addClass("act");
                    $(this).siblings().removeClass("act");
                });

                //选中“会议”－“全部”
                $("#meeting_total").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='2']").addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })

                //选中“会议”－“未交订金”
                $("#meeting_non_front").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='2']").addClass("hid");
                    $("[order-type='1']").find("[order_status='2']").parent().addClass("hid");
                    $("[order-type='1']").find("[order_status='3']").parent().addClass("hid");
                    $("[order-type='1']").find("[order_status='4']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })

                //选中“会议”－“已交订金”
                $("#meeting_front").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='2']").addClass("hid");
                    $("[order-type='1']").find("[order_status='1']").parent().addClass("hid");
                    $("[order-type='1']").find("[order_status='3']").parent().addClass("hid");
                    $("[order-type='1']").find("[order_status='4']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })

                //选中“会议”－“已完成”
                $("#meeting_end").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='2']").addClass("hid");
                    $("[order-type='1']").find("[order_status='2']").parent().addClass("hid");
                    $("[order-type='1']").find("[order_status='3']").parent().addClass("hid");
                    $("[order-type='1']").find("[order_status='1']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_status = $(this).find("span").attr("order_status");
                        var order_type = $(this).attr("order-type");
                        var order_id = $(this).attr("order-id");
                        plan_jump (order_type,order_status,order_id);
                    });

                })
            });

        }else if("<?php echo $_GET['t'];?>" == "design"){
            
            var html_tab = '<div class="tab_module fixed4" id="second_tab"><p class="tab_btn act" id="second_total"><span>全部</span></p><p class="tab_btn" id="front"><span>已交订金</span></p><p class="tab_btn" id="middle"><span>付中期款</span></p><p class="tab_btn" id="end"><span>已完成</span></p></div>'
            $("#top_tab").after(html_tab);
            $("#top_tab").remove();

            //打印订单列表
                var html;
                <?php   //全部婚礼订单
                $html = '';
                foreach ($arr_order as $key => $value) {
                    $html .= shuchu_html($value,$value['order_status']);
                }
                echo "html ='" . $html . "';";
                ?>

                $(".order_list").empty(); //清空订单列表
                $(".order_list").prepend(html); //打印新的订单列表
                $("[order-type='1']").addClass("hid");
                order_status_xuanran ();
                buttonclick_xuanran();
                $(".ulist_item").on("click", function () {
                    //判断order－type，进入不同页面
                    var order_id = $(this).attr("order-id");
                    design_jump (order_id);
                });

                //点击变换导航选中状态
                $(".tab_btn").on("click", function () {
                    $(this).addClass("act");
                    $(this).siblings().removeClass("act");
                });

                //选中“婚礼”－“全部”
                $("#second_total").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_id = $(this).attr("order-id");
                        design_jump (order_id);
                    });

                })

                //选中“婚礼”－“已交订金”
                $("#front").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    $("[order-type='2']").find("[order_status='1']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='3']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='4']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_id = $(this).attr("order-id");
                        design_jump (order_id);
                    });

                })

                //选中“婚礼”－“已交中期款”
                $("#middle").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    $("[order-type='2']").find("[order_status='1']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='2']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='4']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_id = $(this).attr("order-id");
                        design_jump (order_id);
                    });

                })

                //选中“婚礼”－“已完成”
                $("#end").on("click", function () {
                    $(".ulist_item").removeClass("hid");
                    $("[order-type='1']").addClass("hid");
                    $("[order-type='2']").find("[order_status='1']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='2']").parent().addClass("hid");
                    $("[order-type='2']").find("[order_status='3']").parent().addClass("hid");
                    order_status_xuanran ();
                    buttonclick_xuanran();
                    $(".ulist_item").on("click", function () {
                        //判断order－type，进入不同页面
                        var order_id = $(this).attr("order-id");
                        design_jump (order_id);
                    });

                })
        };

        

        //订单状态按钮，初始渲染
        /*$("[order_status='1']").find(".lcs_checkbox_switch").removeClass('lcs_off');
        $("[order_status='1']").find(".lcs_checkbox_switch").addClass('lcs_on');*/

        function buttonclick_xuanran(){
            $('#switch').lc_switch();
            // triggered each time a field changes status
            $('body').delegate('.lcs_check', 'lcs-statuschange', function() {
            var status = ($(this).is(':checked')) ? 'checked' : 'unchecked';
            console.log('field changed status: '+ status );
            });

            // triggered each time a field is checked
            $('body').delegate('.lcs_check', 'lcs-on', function() {
                console.log('field is checked');
                $.post('<?php echo $this->createUrl("order/ChangeOrderStatus");?>',{order_id:$(this).attr("order-id"),order_status:1},function(){
                    alert("档期已预订");
                });
            });

            // triggered each time a is unchecked
            $('body').delegate('.lcs_check', 'lcs-off', function(){
                console.log('field is unchecked');
                $.post('<?php echo $this->createUrl("order/ChangeOrderStatus");?>',{order_id:$(this).attr("order-id"),order_status:0},function(){
                    alert("预订已取消");
                });
            });     
        }
        

        //根据order_status,渲染订单状态
        //订单状态按钮，初始渲染
        $("[order_status='1']").next().removeClass('lcs_off');
        $("[order_status='1']").next().addClass('lcs_on');

        //把对应的订单状态，转换为对应颜色和文字
        function order_status_xuanran () {
            $("[order_status='0']").html("待定");
            $("[order_status='0']").addClass("status_red");

            $("[order_status='1']").html("预定");
            $("[order_status='1']").addClass("status_white");

            $("[order_status='2']").html("已交订金");
            $("[order_status='2']").addClass("status_green");

            $("[order_status='3']").html("付中期款");
            $("[order_status='3']").addClass("status_orange");

            $("[order_status='4']").html("已完成");
            $("[order_status='4']").addClass("status_darkgray");
        };

        //统筹页面，跳转新建订单／订单详情页
        function plan_jump (order_type,order_status,order_id) { 
            /*if(order_status == 0 & order_type == 2){
                location.href = "<?php echo $this->createUrl("plan/customerName");?>&order_id=" + order_id + "&from=my_order&t=plan";
            }else */if(order_type == 2){
                location.href = "<?php echo $this->createUrl("order/orderinfo");?>&order_id=" + order_id + "&from=my_order";
            }/*else if(order_status == 0 & order_type == 1){
                location.href = "<?php echo $this->createUrl("meeting/selectCustomer");?>&company_id=&order_id=" + order_id + "&from=my_order&t=plan";
            }*/else if(order_type == 1){
                location.href = "<?php echo $this->createUrl("order/orderinfo");?>&order_id=" + order_id + "&from=my_order";
            }
        };
        //策划页面，跳转到订单详情页
        function design_jump (order_id) {
            location.href = "<?php echo $this->createUrl("order/orderinfo");?>&order_id=" + order_id + "&from=my_order&t=design";
        };
    })

</script>
</body>
</html>