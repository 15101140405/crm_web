<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>新增供应商</title>
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
        <h2 class="page_title">新增供应商</h2>
    </div>
    <div class="select_ulist_module">
        <h4 class="module_title">选择联系人</h4>
        <ul class="select_ulist">
          <li class="ulist_item flex">
              姓名
              <div class="flex1">
                  <input class="align_r t_green" id="na" type="text" placeholder="请输入供应商姓名"/>
              </div>
          </li>
          <li class="ulist_item flex">
              联系电话
              <div class="flex1">
                  <input class="align_r t_green" id="telephone" type="text" placeholder="请输入联系电话"/>
              </div>
          </li>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
        <div class="r_btn" id="sure">确定</div>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
      $("#sure").on("click",function(){
        var mydate = new Date();
        var time = mydate.toLocaleDateString();
        var data = {
            name : $("#na").val(),
            telephone : $("#telephone").val(),
            supplier_type : 20,
            update_time : time
        };
        /*if(data.)*/
        $.post("<?php echo $this->createUrl("design/supplier_add");?>",data,function(){
            location.href="<?php echo $this->createUrl("design/select_supplier");?>&type=<?php echo $_GET['type']?>&from=<?php $_GET['from']?>&order_id=<?php echo $_GET['order_id']?>";
        });
      });  
    });

</script>
</body>
</html>
