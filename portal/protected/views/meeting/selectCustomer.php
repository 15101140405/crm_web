<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>会议－选择客户</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link href="css/base.css" rel="stylesheet" type="text/css"/>
    <link href="css/style.css" rel="stylesheet" type="text/css"/>
    <link href="css/calendar.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">选择客户公司</h2>
        <div class="r_btn" data-icon="&#xe6a3;"></div>
    </div>

    <!-- 搜索
       <div class="search_module">
           <input type="hidden" id="default_kwd" value="${default}">
           <div class="search_bar">
               <p class="cancle">取消</p>
               <div class="search">
                   <div class="search_btn" data-icon="&#xe65c;"></div>
                   <div class="search_clear" data-icon="&#xe658;"></div>
                   <div class="search_input_bar">
                       <input id="search_input" type="search" placeholder="${default}"/>
                   </div>
               </div>
           </div>
           <div class="search_show">
               ／／未输入状态
               <div class="history">
                   <ul class="u_list">
                   </ul>
                   <a class="clear_btn">清空搜索历史</a>
               </div>
               ／／自动匹配状态
               <div class="search_sug">
                   <ul class="u_list">
                       <li class="keyword">输入的文字<span class="import" data-icon="&#xe767;"></span></li>
                   </ul>
               </div>
           </div>
       </div>
       搜索 end -->

    <div class="btn add_customer">+ 新增客户公司</div>

    <div class="select_ulist_module">
        <h4 class="module_title">选择客户公司</h4>
        <ul class="select_ulist">
        <?php
        foreach ($arr_old_customer as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $customer[$key1]=$value1;
            }
        ?>
            <li class="select_ulist_item select" data-id="<?php echo $customer['company_id'];?>" aa="<?php echo $customer['order_id'];?>"><?php echo $customer['company_name'];?></li>
        <?php
        }
        ?>
        </ul>
    </div>
    <div class="bottom_fixed_bar" id='bottom'>
        <div class="r_btn" id="select_customer">确定</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<!-- 搜索的交互方案待定 -->
<script src="js/search.js"></script>
<script src="js/common.js"></script>
<script>
  $(function () {
    //初始化
    var selected_id = "<?php if(isset($_GET['company_id'])){echo $_GET['company_id'];}?>";
    var selected_company = "<?php echo $selected_company?>";
    $("li").removeClass("select_selected");
    $("[data-id='"+selected_company+"']").addClass("select_selected");
    if("<?php echo $_GET['from']?>" == "detailinfo"){
      $(".l_btn").remove();
      $(".r_btn").html("确定");
      $(".r_btn").attr("data-icon","");
    };
    // alert("<?php echo $_GET['from']?>");
    if("<?php echo $_GET['from']?>" == "product_store"){
      // alert(1);
      $("#back").remove();
      $("#next").remove();
    }else{
      $("#bottom").remove();
    };


    if(selected_id != ""){
      /*$("li").removeClass("select_selected");
      $("[data-id='"+selected_id+"']").addClass("select_selected");*/
    }
    
    /*$('ul').find('li:first').addClass("select_selected");*/

    //客户选择勾选
    $(".select_ulist li").on("click", function () {
      $(".select_ulist li").removeClass("select_selected");
      $(this).addClass("select_selected");
      // $(this).html();
    });

    //返回按钮
    $(".l_btn").on("click", function () {
      //清空localStorage
      localStorage.removeItem("choose_customer");
      location.href = "<?php echo $this->createUrl("order/selectType");?>";
    });

    //确定按钮
    if("<?php echo $_GET['from']?>" == "selecttime"){
      $(".r_btn").on("click", function () {
        var choose_obj = $(".select_ulist .select_selected");
        if($('.select_ulist_item').hasClass("select_selected")){
          location.href = "<?php echo $this->createUrl("meeting/selectLinkman");?>&from=selectcustomer&linkman_id=&order_id=<?php  if(isset($_GET['order_id'])){echo $_GET['order_id'];}?>&company_id="+choose_obj.attr("data-id");
        }else{
          alert("请先选择客户！");
        }
      });
    }else if("<?php echo $_GET['from']?>" == "detailinfo"){
      $(".r_btn").on("click", function () {
        var choose_obj = $(".select_ulist .select_selected");
        console.log({order_id:"<?php if(isset($_GET['order_id'])){echo $_GET['order_id'];}?>",company_id:choose_obj.attr("data-id")});
        $.post("<?php echo $this->createUrl("meeting/updatecompanyid");?>",{order_id:"<?php if(isset($_GET['order_id'])){echo $_GET['order_id'];}?>",company_id:choose_obj.attr("data-id")},function(){
          location.href = "<?php echo $this->createUrl("meeting/detailinfo");?>&order_id=<?php if(isset($_GET['order_id'])){echo $_GET['order_id'];}?>";
        });
      });
    }
    //新增按钮
    $(".btn").on("click", function () {
      var choose_obj = $(".select_ulist .select_selected");
      location.href = '<?php echo $this->createUrl("meeting/addCustomer"); ?>&from=<?php echo $_GET['from']?>&order_id=<?php if(isset($_GET['order_id'])){echo $_GET['order_id'];}?>&company_id='+choose_obj.attr("data-id");
    });

    //确定，返回产品库新增订单
    $("#select_customer").on("click",function(){
      var choose_obj = $(".select_ulist .select_selected");
      location.href = "<?php echo $this->createUrl("product/createorder");?>&set_id=<?php echo $_GET['set_id']?>&product_id=<?php echo $_GET['product_id']?>&from=<?php echo $_GET['from']?>&category=<?php echo $_GET['category']?>&company_id="+choose_obj.attr("data-id");
    });

  });

</script>
</body>
</html>
