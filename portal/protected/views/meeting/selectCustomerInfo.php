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
        <h2 class="page_title">选择客户</h2>
        <div class="r_btn">保存</div>
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
        ／／ 未输入状态
        <div class="history">
          <ul class="u_list">
          </ul>
          <a class="clear_btn">清空搜索历史</a>
        </div>
        ／／ 自动匹配状态
        <div class="search_sug">
          <ul class="u_list">
            <li class="keyword">输入的文字<span class="import" data-icon="&#xe767;"></span></li>
          </ul>
        </div>
      </div>
    </div>
    搜索 end -->

    <div class="btn add_customer" id="add">+ 新增客户</div>

    <div class="select_ulist_module">
        <h4 class="module_title">选择客户</h4>
        <ul class="select_ulist">
            <!-- <li class="select_ulist_item select select_selected">小王</li>
            <li class="select_ulist_item select">校长</li> -->
        </ul>
    </div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/zepto.cookie.js"></script>
<!-- 搜索的交互方案待定 -->
<script src="js/search.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {

        init();

        function init() {
            var new_customer = $.parseJSON(localStorage.getItem("new_customer"));
            var old_customer_html = $.parseJSON(localStorage.getItem("old_customer_html"));
            var choose_customer = $.parseJSON(localStorage.getItem("choose_customer"));

            //如果从新建客户页面进入，需将新建的客户至于最上方
            if (new_customer != null) {
                $(".select_ulist li").removeClass("select_selected");
                //渲染添加的新客户，此客户已存入数据库
                for (var i = 0; i < new_customer.length; i++) {
                    var html = '<li class="select_ulist_item select" data-id="' + new_customer[i].c_id + '">' + new_customer[i].c_name + '</li>';
                    ;
                    $(".select_ulist").prepend(html);
                }
            }
            if (old_customer_html != null) {
                //读取old_customer_html，渲染数据库中的客户
                $(".select_ulist").append(old_customer_html);
            }
            else {//从数据库读取

                //显示从数据库中获取的客户信息
                //$.getJSON('#',function(retval){
                //  if(retval.success){
                <?php
                $arr_old_customer = array(
                    '0' => array(
                        'c_id' => '0',
                        'c_name' => '小周'
                    ),
                    '1' => array(
                        'c_id' => '1',
                        'c_name' => '浩瀚一方'
                    )
                );
                $arr_old_customer_json = json_encode($arr_old_customer);
                ?>

                //var old_customer = [{c_id:0,c_name:"小王1"},{c_id:1,c_name:"浩瀚一1方"}];
                var old_customer = <?php echo $arr_old_customer_json; ?>;
                for (var i = 0; i < old_customer.length; i++) {
                    var html = '<li class="select_ulist_item select" data-id="' + old_customer[i].c_id + '">' + old_customer[i].c_name + '</li>';
                    $(".select_ulist").append(html);
                }
                //将读取到的客户信息渲染出的html代码临时存到本地存储old_customer中，整个流程结束remove
                localStorage.setItem("old_customer_html", JSON.stringify($(".select_ulist").html()));
                //  }else{
                //    alert('太累了，歇一歇，刷新下试试！');
                //    return false;
                //  }
            }


            //判断哪个客户被勾选
            if (choose_customer != null) {
                $(".select_ulist li").eq(choose_customer.c_place).addClass('select_selected');
            } else {
                $(".select_ulist li").eq(0).addClass('select_selected');
            }
        }

        //客户选择勾选
        $(".select_ulist li").on("click", function () {
            $(".select_ulist li").removeClass("select_selected");
            $(this).addClass("select_selected");
        });

        //返回按钮
        $(".l_btn").on("click", function () {
            //清空新增客户(已存数据库)，已有客户，选择客户
            localStorage.removeItem("new_customer");
            localStorage.removeItem("old_customer_html");
            localStorage.removeItem("choose_customer");
            location.href = "<?php echo $this->createUrl("meeting/detailInfo", array());?>";
        });

        //保存按钮
        $(".r_btn").on("click", function () { //backgroun data
            /*$.postJASON("#",{"order_id":localStorage.getItem("order_id"),"customer":$("li.select").html()},function(retval){
             if(retval.success){*/
            location.href = "<?php echo $this->createUrl("meeting/detailInfo", array());?>";
            /*}
             else
             {
             alert("保存失败，再试一次！")
             }
             })*/
        });

        //新增按钮
        $("#add").on("click", function () {
            //跳转到新建客户meeting_add_customer
            location.href = "<?php echo $this->createUrl("meeting/addCustomer", array());?>&from=info";
        });

    })

</script>
</body>
</html>
