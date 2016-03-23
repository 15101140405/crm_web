<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>会议－选择联系人</title>
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
        <h2 class="page_title">选择联系人</h2>
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

    <div class="btn add_customer" id="add">+ 新增联系人</div>

    <div class="select_ulist_module">
        <h4 class="module_title">选择联系人</h4>
        <ul class="select_ulist">
            <!--       <li class="select_ulist_item select">小王</li>
                  <li class="select_ulist_item select select_selected">校长</li> -->
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
            var new_linkman = $.parseJSON(localStorage.getItem("new_linkman"));
            var old_linkman_html = $.parseJSON(localStorage.getItem("old_linkman_html"));
            var choose_linkman = $.parseJSON(localStorage.getItem("choose_linkman"));
            console.log(new_linkman);
            console.log(old_linkman_html);
            console.log(choose_linkman);

            //如果从新建客户页面进入，需将新建的客户至于最上方
            if (new_linkman != null) {
                $(".select_ulist li").removeClass("select_selected");
                //渲染添加的新客户，此客户已存入数据库
                for (var i = 0; i < new_linkman.length; i++) {
                    var html = '<li class="select_ulist_item select" data-id="' + new_linkman[i].c_id + '">' + new_linkman[i].c_name + '</li>';
                    ;
                    $(".select_ulist").prepend(html);
                }
            }
            if (old_linkman_html != null) {
                //读取old_linkman_html，渲染数据库中的客户
                $(".select_ulist").append(old_linkman_html);
            }
            else {//从数据库读取

                //显示从数据库中获取的客户信息 //background data
                //$.getJSON('#',function(retval){
                //	if(retval.success){
                var old_linkman = [{c_id: 0, c_name: "联系人1"}, {c_id: 1, c_name: "浩瀚一方"}];
                for (var i = 0; i < old_linkman.length; i++) {
                    var html = '<li class="select_ulist_item select" data-id="' + old_linkman[i].c_id + '">' + old_linkman[i].c_name + '</li>';
                    $(".select_ulist").append(html);
                }
                //将读取到的客户信息渲染出的html代码临时存到本地存储old_linkman中，整个流程结束remove
                localStorage.setItem("old_linkman_html", JSON.stringify($(".select_ulist").html()));
                //	}else{
                //		alert('太累了，歇一歇，刷新下试试！');
                //		return false;
                //	}
            }

            //判断哪个客户被勾选
            if (choose_linkman != null) {
                $(".select_ulist li").eq(choose_linkman.c_place).addClass('select_selected');
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
            localStorage.removeItem("new_linkman");
            localStorage.removeItem("old_linkman_html");
            localStorage.removeItem("choose_linkman");
            location.href = "<?php echo $this->createUrl("meeting/detailInfo", array());?>";
        });

        //保存按钮
        $(".r_btn").on("click", function () {
            /*$.postJASON("#",{"order_id":localStorage.getItem("order_id"),"linkman":$("li.select_selected").html()},function(retval){
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
            //跳转到新建客户meeting_add_linkman
            location.href = "<?php echo $this->createUrl("meeting/addLinkman", array());?>&from=info";
        });
    });

</script>
</body>
</html>
