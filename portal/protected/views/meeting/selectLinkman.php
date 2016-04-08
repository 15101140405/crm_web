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
              ／／未输入状态       <div class="history">
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

    <a  class="btn add_customer">+ 新增联系人</a>

    <div class="select_ulist_module">
        <h4 class="module_title">选择联系人</h4>
        <ul class="select_ulist">
        <?php
        foreach ($arr_old_linkman as $key => $value) {
            foreach ($value as $key1 => $value1) {
                $linkman[$key1]=$value1;
            }
        ?>
            <li class="select_ulist_item select" data-id="<?php echo $linkman['linkman_id'];?>" aa="<?php echo $linkman['company_id'];?>"><?php echo $linkman['linkman_name'];?></li>
        <?php
        }
        ?>
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
      //初始渲染
      if("<?php echo $_GET['linkman_id']?>" != ""){
        $("[data-id='<?php echo $_GET['linkman_id']?>']").addClass("select_selected");
      }
      if("<?php echo $_GET['from']?>" == "detailinfo"){
        $(".l_btn").remove();
        $(".r_btn").html("确定");
        $(".r_btn").attr("data-icon","");
      };

      //客户选择勾选
      $(".select_ulist li").on("click", function () {
          $(".select_ulist li").removeClass("select_selected");
          $(this).addClass("select_selected");
      });

      //返回按钮
      $(".l_btn").on("click", function () {
          //清空localStorage
          localStorage.removeItem("choose_customer");
          location.href = "<?php echo $this->createUrl("meeting/selectCustomer");?>&order_id=<?php echo $_GET['order_id']?>&company_id=<?php echo $_GET['company_id']?>";
      });

      //确定按钮
      if("<?php echo $_GET['from']?>" == "selectcustomer"){
        $(".r_btn").on("click", function () {
            var mydate = new Date();
            var year = mydate.getFullYear() + "";
            var month = mydate.getMonth() + 1;
            var month = month + "";
            var date = mydate.getDate() + "";
            var hours = mydate.getHours() + "";
            var minutes = mydate.getMinutes() + "";
            var seconds = mydate.getSeconds() + "";

            var time = year + "-" + month + "-" + date + " " + hours + "-" + minutes + "-" + seconds;

            var choose_obj = $(".select_ulist .select_selected");

            var meeting_info = {
                  account_id : <?php echo $_SESSION['account_id']?>,
                  order_id : <?php echo $_GET['order_id'];?>,
                  company_id : <?php echo $_GET['company_id'];?>,
                  company_linkman_id : parseInt(choose_obj.attr("data-id")),
                  layout_id : 1,
                  update_time : time
                }
            console.log(meeting_info);

            $.post("<?php echo $this->createUrl('meeting/meetingdetailinsert')?>",meeting_info,function(retval){
              if($('.select_ulist_item').hasClass("select_selected")){
                location.href = "<?php echo $this->createUrl("order/my");?>&code=&t=plan&order_id=<?php echo $_GET['order_id']?>";
              }else{
                alert("请先选择联系人！");
              };
            })
            // location.href = "<?php echo $this->createUrl("meeting/selectLayout");?>&linkman_id="+linkman_id+"&company_id="+aa;
        });
      }else{
        $(".r_btn").on("click", function () {
          var choose_obj = $(".select_ulist .select_selected");
          $.post("<?php echo $this->createUrl('meeting/updatelinkmanid')?>",{order_id:"<?php echo $_GET['order_id']?>",linkman_id:choose_obj.attr("data-id")},function(retval){
            if($('.select_ulist_item').hasClass("select_selected")){
              location.href = "<?php echo $this->createUrl("meeting/detailinfo");?>&order_id=<?php echo $_GET['order_id']?>";
            }else{
              alert("请先选择联系人！");
            };
          });
            // location.href = "<?php echo $this->createUrl("meeting/selectLayout");?>&linkman_id="+linkman_id+"&company_id="+aa;
        });
      }
        

      //新增按钮
      $(".btn").on("click", function () {
          location.href="<?php echo $this->createUrl("meeting/addLinkman");?>&from=<?php echo $_GET['from']?>&order_id=<?php echo $_GET['order_id']?>&company_id=<?php echo $_GET['company_id']?>"
      });

    });
</script>
</body>
</html>