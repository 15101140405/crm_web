<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>输入折扣</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="css/base.css" rel="stylesheet" type="text/css" />
<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<article style='position: relative;bottom: 200px;top: 1px;'>
    <div class="tool_bar">
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">输入折扣</h2>
    </div>
    <div class="ulist_module">
        <ul class="ulist">
            <li class="ulist_item flex" id="2">
                折扣
                <div class="flex1">
                    <input id="discount" class="align_r" type="text" placeholder="输入折扣，如 8.5"/>
                </div>
                <i class="mar_l10 t_green">折</i>
            </li>
        </ul>
    </div>
    <div class="select_ulist_module" id="supplier_type">
        <ul class="select_ulist">
            <li class="select_ulist_item round_select round_select_selected" value="17">策划费</li>
            <li class="select_ulist_item round_select round_select_selected" value="19">场地费</li>
            <li class="select_ulist_item round_select round_select_selected" value="20">场地布置</li>
            <li class="select_ulist_item round_select round_select_selected" value="3">服务人员</li>
            <!-- <li class="select_ulist_item round_select round_select_selected" value="4">摄像</li>
            <li class="select_ulist_item round_select round_select_selected" value="5">摄影</li>
            <li class="select_ulist_item round_select round_select_selected" value="6">化妆</li>
            <li class="select_ulist_item round_select round_select_selected" value="7">其他</li> -->
            <li class="select_ulist_item round_select round_select_selected" value="8">灯光</li>
            <li class="select_ulist_item round_select round_select_selected" value="9">视频</li>
            <li class="select_ulist_item round_select round_select_selected" value="10">平面设计</li>
            <li class="select_ulist_item round_select round_select_selected" value="11">视频设计</li>
            <li class="select_ulist_item round_select round_select_selected" value="12">婚纱礼服</li>
            <li class="select_ulist_item round_select round_select_selected" value="13">婚礼用品</li>
            <li class="select_ulist_item round_select round_select_selected" value="14">婚礼酒水</li>
            <li class="select_ulist_item round_select round_select_selected" value="15">婚礼车辆</li>
            <li class="select_ulist_item round_select " value="18">税费</li>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
    	<a class="r_btn" id="sure">确认收款</a>
  	</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js" type="text/javascript"></script>
<script>
    $(function () {

        //初始渲染
        if("<?php echo $_GET['type']?>" == "feast"){
            $("#supplier_type").remove();
            if(<?php echo $feast_discount?> != 10){
                $("#discount").val(<?php echo $feast_discount?>);
            }
        }else if("<?php echo $_GET['type']?>" == "meeting_other"){
            $("[value='17']").remove();
            $("[value='20']").remove();
            $("[value='3']").remove();
            $("[value='4']").remove();
            $("[value='5']").remove();
            $("[value='6']").remove();
            $("[value='7']").remove();
            $("[value='10']").remove();
            $("[value='11']").remove();
            $("[value='12']").remove();
            $("[value='13']").remove();
            $("[value='14']").remove();
            $("[value='15']").remove();
            if(<?php echo $other_discount?> != 10){
                $("#discount").val(<?php echo $other_discount?>);
            }
        
            var i = 0;
            <?php $t = explode(",",$discount_range); array_pop($t);  ?>
            $("li").each(function(){
            <?php 
                foreach ($t as $key => $value) {
            ?>
                if($(this).val() == <?php echo $value; ?>){
                    i++;
                }
            <?php }?>    
                if(i == 0){
                    $(this).removeClass("round_select_selected");
                }    
                i = 0;
            });
        
        }else if("<?php echo $_GET['type']?>" == "wedding_other"){
            $("[value='19']").remove();
            if(<?php echo $other_discount?> != 10){
                $("#discount").val(<?php echo $other_discount?>);
            }

            $("li").each(function(){
            <?php 
                foreach ($t as $key => $value) {
            ?>
                if($(this).val() == <?php echo $value; ?>){
                    i++;
                }
            <?php }?>    
                if(i == 0){
                    $(this).removeClass("round_select_selected");
                }    
                i = 0;
            });
        }
        
        
        //点击确认收款
        $("#sure").on("click",function(){
            var t=getData();
            console.log(t);
            if(t['discount_range'] == 'empty'){
                alert("至少选择一个产品类别！");
                return false;
            }
            data = {discount:t['discount'],discount_range:t['discount_range'],order_id:<?php echo $_GET['order_id']?>};
            console.log(data);
            $.post('<?php echo $this->createUrl("order/discount");?>',data,function(retval){
                //alert(retval);
               // if(retval.success){
                if("<?php echo $_GET['from1']?>" == 'meeting'){
                    location.href='<?php echo $this->createUrl("meeting/bill");?>&order_id=<?php echo $_GET["order_id"];?>&from=<?php echo $_GET["from"];?>';
                }else{
                    location.href='<?php echo $this->createUrl("design/bill");?>&order_id=<?php echo $_GET["order_id"];?>&from=<?php echo $_GET["from"];?>';
                }
               // }else{
               //   alert('太累了，歇一歇，一秒后再试试！');
                //     return false;
                //   }
            });
        });

        //选择收款方式
        $(".select_ulist li").on("click", function () {
            if(!$(this).hasClass("round_select_selected")){
                $(this).addClass("round_select_selected");
            } else {
                $(this).removeClass("round_select_selected");
            }          
        });

        function getData(){
            var result = {
                discount_range : "",
                discount : "",
            }
            if("<?php echo $_GET['type']?>" == "meeting_other" || "<?php echo $_GET['type']?>" == "wedding_other" ){
                var discount_range = "";
                $("li").each(function(){
                    if($(this).hasClass("round_select_selected")){
                        discount_range += $(this).val()+",";
                    }
                });
                if(discount_range == ""){
                    discount_range = "empty";
                }
                var result = {
                    discount_range : discount_range,
                    discount : $("#discount").val()
                };
            }else if("<?php echo $_GET['type']?>" == "feast"){
                var discount_range = "";
                var result = {
                    discount_range : discount_range,
                    discount : $("#discount").val(),
                };
            }
            return result;
        }
    })   
</script>
</body>
</html>
