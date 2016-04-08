<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>会议－新建客户</title>
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
        <h2 class="page_title">新建客户</h2>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item customer_name">
                <span class="label">客户名称</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="请输入客户名称"/>
                </div>
            </li>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
        <a class="r_btn">确定</a>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {

        //返回按钮
        $(".l_btn").on("click", function () {
            location.href = "<?php echo $this->createUrl("meeting/selectCustomer");?>&order_id=<?php echo $_GET['order_id']?>&company_id=<?php if($_GET['company_id'] != ""){ echo $_GET['company_id'] ;}?>";
        });

        //确定按钮
        $(".r_btn").on("click", function () {
            //获取新增客户列表
            var new_customer = $.parseJSON(localStorage.getItem("new_customer"));
            var orderId = localStorage.getItem("order_id");
            // alert(orderId);
            //存入数据库且存入本地存储，返回存入客户的ID
            $.post('<?php echo $this->createUrl("meeting/insert");?>',{new_customer:$(".customer_name input").val()},function(retval){
            // $.postJSON('<?php echo $this->createUrl("meeting/InsertCustomer");?>',{new_customer:$(".customer_name input").val()},function(retval){
             // if(retval.success){
            //存入本地存储，能显示在列表最上方
            //将本次增加的push进去
            // alert(retval);
            // if (new_customer != null) {
            //     new_customer[new_customer.length] = {c_id: 3, c_name: $(".customer_name input").val()};
            // } else {
            //     new_customer = [{c_id: 3, c_name: $(".customer_name input").val()}];
            // }

            // localStorage.setItem("new_customer", JSON.stringify(new_customer));
            location.href = "<?php echo $this->createUrl("meeting/selectCustomer");?>&from=<?php echo $_GET['from']?>&order_id=<?php echo $_GET['order_id']?>&company_id=<?php if($_GET['company_id'] != ""){ echo $_GET['company_id'] ;}?>";
            //  }else{
            //    alert('太累了，歇一歇，刷新下试试！');
            //    return false;
             // }

        });

    });
    })
</script>
</body>
</html>
