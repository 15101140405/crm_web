<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>选择部门</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
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
<article>
  <div class="tool_bar">
    <div class="l_btn" data-icon="&#xe679;"></div>
    <h2 class="page_title">新增联系人</h2>
  </div>
  <div class="select_ulist_module pad_b50">
    <ul class="select_ulist" >
      <li class="select_ulist_item " style="line-height:70px;font-size: 20px;">预定档期
        <p style="display:inline-block;float:right;position: relative;top: -20px;">
          <input type="checkbox" name="check-1" value="4" class="lcs_check" autocomplete="off" />
        </p>
      </li>
      <li></li>
    </ul>
    <ul class="select_ulist" >
      <li class="select_ulist_item list_more1 " style="line-height:70px;font-size: 20px;">收款</li>
      <li></li>
    </ul>
  </div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/utility/util.js" type="text/javascript"></script>
<script src="js/jquery.js"></script>
<script src="js/lc_switch.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(e) {
  $('input').lc_switch();
  // triggered each time a field changes status
  $('body').delegate('.lcs_check', 'lcs-statuschange', function() {
    var status = ($(this).is(':checked')) ? 'checked' : 'unchecked';
    console.log('field changed status: '+ status );
  });

  // triggered each time a field is checked
  $('body').delegate('.lcs_check', 'lcs-on', function() {
    console.log('field is checked');
  });
  
  
  // triggered each time a is unchecked
  $('body').delegate('.lcs_check', 'lcs-off', function() {
    console.log('field is unchecked');
  });
});
</script> 

<script>
$(function () {
  //点击收款
  $(".list_more").on("click",function(){
    location.href = "<?php echo $this->createUrl('finance/cashier', array());?>&order_id=<?php echo $_GET['order_id']?>"
  });

  //点击确认
  $(".btn").on("click",function(){
    var choosePosition =  escape($(".round_select_selected").html());
    location.href = "add_usr.html?choosePosition=" + choosePosition;
  });

  //点击返回
  $(".l_btn").on("click",function(){
    var u_info = $.parseJSON(localStorage.getItem("u_info"));
    var choosePosition = escape(u_info.departmen);
    location.href = "add_usr.html?choosePosition=" + choosePosition;
  });
});
</script>

</body>
</html>
