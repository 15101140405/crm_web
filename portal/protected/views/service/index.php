<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>CRM</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/calendar.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />

</head>
<body>
  <div class="header">
    <!-- 当从my_order进去才显示返回按钮 -->
    <!-- <div class="l_btn" data-icon="&#xe679;"></div>  -->
    <h2 class="page_title">
    <?php 
        $user_type = "策划"; //用户类型+++++++++++++++++++++++++++所需数据
      if($user_type == "前台" ){ //当用户类型为“前台”时
          $arr_hotel = array(//+++++++++++++++++++++++++++所需数据
                        'hotel_name'   => '大郊亭店',
                        'staff_hotel_id'=> '1'
                      );
          echo '<select disabled="disabled">'.
               "<option hotel_id='".$arr_hotel['staff_hotel_id']."'>".$arr_hotel['hotel_name']."</option>".
               '</select>';
      }else if($user_type=="策划" || $user_type=="收银"){//用户类型为“策划”／“收银”时
         $arr_hotel = array(//+++++++++++++++++++++++++++所需数据
                     '0' => array(
                                'hotel_name'   => '大郊亭店',
                                'staff_hotel_id'=> '1'
                            ),
                     '1' => array(
                                'hotel_name'   => '航天桥店',
                                'staff_hotel_id'=> '2'
                            )
                     );
        echo  '<select>';
        foreach ($arr_hotel as $key => $value) {
              foreach ($value as $key1 => $value1) {
                      $arr1[$key1] = $value1;
              }
              echo "<option hotel_id='".$arr1['staff_hotel_id']."'>".$arr1['hotel_name']."</option>";
        }
        echo '</select>';
      }
  ?> 
    </h2>

    <!-- 当从my_order进入，则显示下一步按钮 -->
    <!-- <div class="r_btn" data-icon="&#xe767;"></div> -->
    
  </div>
  <!-- 日历 -->
  <section class="calendar" id="calendar" data-id="2">
    <div class="month_control">
      <span class="prev" id="prev_m" data-icon="&#xf011d;"></span>
      <h4 class="calendar_title"><span class="year">2015年</span><span class="month">8月</span></h4>
      <span class="next" id="next_m" data-icon="&#xf011b;"></span>
    </div>
    <div class="date_container">
      <ul class="week">
        <li class="days_title weekend" data-id="6">日</li>
        <li class="days_title" data-id="0">一</li>
        <li class="days_title" data-id="1">二</li>
        <li class="days_title" data-id="2">三</li>
        <li class="days_title" data-id="3">四</li>
        <li class="days_title" data-id="4">五</li>
        <li class="days_title weekend" data-id="5">六</li>
      </ul>
      <ol class="dates">
      </ol>
    </div>
  </section>
  <!-- 预定情况 -->
  <div class="day_order_module">
    <h4 class="module_title">16日预订情况<div class="btn" style="display:inline-block;float:right;height: 2.5rem;font-size: 1.5rem;line-height: 2.5rem;" id="new">新增订单</div></h4>
    <ul class="order_ulist" id='order'>
    </ul>
  </div>

<script src="js/zepto.min.js"></script>
<script src="js/zepto.calendar.js"></script>
<script src="js/common.js"></script>
<script>
$(function() {
  var first_show_year = <?php echo $first_show_year ;?>;
  var first_show_month = <?php echo $first_show_month ;?> -1;
  var first_show_day = <?php echo $first_show_day ;?>;

  comePage();

  //日历初始化
  $("#calendar").almanac({
    /**
     * 画日历之后调用函数
     */
    currYear: first_show_year,
    currMonth: first_show_month,
    afterDrawCld: function(year, month){
      var account_id=<?php echo $_SESSION['account_id']?>;
      var staff_hotel_id=<?php echo $_SESSION['staff_hotel_id']?>;
      console.log(account_id+"|"+staff_hotel_id);
               
      var info ={
        'year': year ,
        'month' : month+1 ,
        'account_id' :  account_id,
        'staff_hotel_id' : staff_hotel_id
      }
      console.log(info);

      /*＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
      //日历部分，增／减月份，渲染
      /*＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
      $.post('<?php echo $this->createUrl("order/getindexdata");?>',info,function(data){
        var order = data.split("|") 

        console.log(order);
        arr_order = { 
          'data' : order[0],
          'half_data' : order[1],//有订单（不是新订单）
          'maybe_data' : order[2]//有新订单
        };

        var data = arr_order["data"]; //模拟的返回值 string
        var order_date = data.split(',')
        var l = order_date.length;
        for(i = 0; i < l; i++){
          //前端给日历对应的日期加标记
          $('li[data-solor="'+order_date[i]+'"]').addClass('order');
        }
        //半定日期
        var half_data = arr_order["half_data"]; //模拟的返回值 string
        var half_order_date = half_data.split(',')
        var l = half_order_date.length;
        for(i = 0; i < l; i++){
          //前端给日历对应的日期加标记
          $('li[data-solor="'+half_order_date[i]+'"]').addClass('half_order');
        }
        //有新单日期
        var maybe_data = arr_order["maybe_data"]; //模拟的返回值 string
        var maybe_order_date = maybe_data.split(',')
        var l = maybe_order_date.length;
        for(i = 0; i < l; i++){
          var _this = $('li[data-solor="'+maybe_order_date[i]+'"]');
          if(!_this.hasClass('half_order')){
            //前端给日历对应的日期加标记
            _this.addClass('maybe_order');
          }
        }
      });

      /*＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
      //订单部分，增／减月份，渲染
      /*＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝＝*/
      $.ajax({ 
        type: "POST", 
        url: "<?php echo $this->createUrl("order/findmonthorder");?>", 
        data: info,
        contentType: "application/x-www-form-urlencoded; charset=utf-8", 
        success:function(retval){
          console.log(retval);
          var temp = eval(retval);
          var order = new Array();
          for(i=0 ; i<temp.length ; i++){
            order[i] = eval(temp[i]);
          };
          console.log(order);

          $('.order_ulist').html('');
            for(var i=0; i<order.length; i++){
              var html = '<li order-id="'+order[i].order_id+'" type="'+order[i].order_type+'" status="'+order[i].order_status+'" day="'+order[i].order_day+'"><span class="order_categroy" >'+order_type_json[order[i].order_type-1].type_content+'</span>';
              /*html += '<div class="order_desc"><p style="display:table;" ><span class="order_planer" style="display:table-cell;">'+order[i].planner_name+'</span>';*/
              html += '<div class="order_desc"><p style="display:table;" >';
              html += '<span class="consumer" style="display:table-cell;">'+order[i].order_name+'</span> <i> ['+order[i].planner_name+']</i></p></div>';
              html += '<span class="index_order_status '+order_status[order[i].order_status].class_type+'">'+order_status[order[i].order_status].content+'</span></li>';
              $('.order_ulist').append(html);
            }
            var html = '<li class="no_order"><div class="order_null">今日无订单</div></li>';
            $('.order_ulist').append(html); 
          //点击订单，跳转订单详情页
          $(".order_ulist li").on("click",function(){
            //判断order－type，进入不同页面
            var order_id = $(this).attr("order-id");
            /*if($(this).attr("status") == "0" && $(this).attr("type") == "1"){
              location.href = 
            }else if($(this).attr("status") == "0" && $(this).attr("type") == "2"){
              location.href = 

            }else */
            if($(this).attr("status") == "0"){//新订单
              if($(this).attr("type") == "1"){
                location.href = "<?php echo $this->createUrl("meeting/selectcustomer", array());?>&order_id="+order_id+"&from=index&company_id=";
              }else if($(this).attr("type") == "2"){
                location.href = "<?php echo $this->createUrl("plan/customername", array());?>&order_id="+order_id+"&from=index";
              };
            }else{
              if($(this).attr("type") == "1"){//老订单
                location.href = "<?php echo $this->createUrl("meeting/bill", array());?>&order_id="+order_id+"&from=index";
              }else if($(this).attr("type") == "2"){
                location.href = "<?php echo $this->createUrl("design/bill", array());?>&order_id="+order_id+"&from=index";
              };
            }
            
          });

          //调用一次clickDay事件，让第一次进入显示当日订单
          if($('.dates').find('.choose_day').length == 0){
            $('li[data-solor="'+first_show_day+'"]').addClass('choose_day');
          }
          show_order($('.choose_day'));


        }
      });

    },
    //afterDrawCld方法结束
    /**
     * 单击某一天的事件
     */
    clickDay: function(elem){
      var _this = $(elem);

      if(_this.hasClass('unover')){
        return;
      }else{
        //增加焦点状态
        $('.dates').find('li').removeClass('choose_day');
        _this.addClass('choose_day');
        first_show_day = 1;
        
        show_order(_this);

      }
    }


  });

  //右上角加号按钮，增加订单
  $(".r_btn").on("click",function(){
    rbtnClick();
  });

  //新增订单
  $(".btn").on("click",function(){
    location.href = "<?php echo $this->createUrl("order/selecttype", array());?>&code=";
  })

  /* ===========================
   * 显示选择日期的订单情况
   * =========================== */
  function show_order(choose_day){
    var _this = choose_day;
    $('.module_title').html(_this.attr('data-solor')+'日预定情况'+'<div class="btn" style="display:inline-block;float:right;height: 2.5rem;font-size: 1.5rem;line-height: 2.5rem;" id="new">新增订单</div>');
    $("#order").find("li").removeClass("hid");
    $("#order").find("li").addClass("hid");
    $("[day='"+_this.attr('data-solor')+"']").removeClass('hid');
    if($('.order_ulist').find('.hid').length == $('.order_ulist').find('li').length){
      $(".no_order").removeClass("hid");
    }
    $(".btn").on("click",function(){
      location.href = "<?php echo $this->createUrl("order/selecttype", array());?>&code=";
    })
  }

  /* ===========================
   * 判断进入页面：my_order进入，则最上方按钮有变化
   * =========================== */
  function comePage(){
    if(unescape($.util.param("from"))=="my_order"){//如果从my_order进入
       var l_btn = '<div class="l_btn" data-icon="&#xe679;"></div>';   
       $('.header .l_btn').remove();
       $('.header').prsepend(l_btn);

      var r_btn = '<div class="r_btn" data-icon="&#xe767;"></div>';   
      $('.header .r_btn').remove();
      $('.header').append(r_btn);
      //$('.header .r_btn').attr("data-icon","&#xe767;");
      
      $('.l_btn').on("click",function(){
        location.href = "my_order.html";
      });

      $(".r_btn").on("click",function(){
        rbtnClick();
      });    
    }
  }
    /* ===========================
   * 右上角图标点击，＋或者>
   * =========================== */
  function rbtnClick(){
    var _this = $(".choose_day");
    var order_date = _this.attr('data-year')+'-'+_this.attr('data-month')+'-'+_this.attr('data-solor');
    localStorage.setItem("new_order_day", order_date);//将焦点日期存入本地存储
    location.href = '<?php echo $this->createUrl("order/selecttype", array());?>';    
   }  

});
</script>
</body>
</html>
