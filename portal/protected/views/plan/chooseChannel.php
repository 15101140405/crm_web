<!DOCTYPE HTML>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>选择推单</title>
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
        <h2 class="page_title">选择推单</h2>
        <div class="r_btn" id='insert'>新增</div>
    </div>
    <div class="select_ulist_module pad_b50">
        <ul class="select_ulist">
            <li product-id=0 class="select_ulist_item round_select <?php if ($select == ""){echo "round_select_selected"; $select['id']=0; }//原为空} ?> ">无</li>
            <?php
            $order_id = $_GET['order_id'];
            $from = $_GET['from'];
            $category = 2;
            if ($from == "meeting"){
                $category = 1;}



            // $arr_supplier = array(
            //     '0' => '喜小宝',
            //     '1' => '到喜啦',
            //     '2' => '易结网',
            //     '3' => '小两口',
            //     '4' => '喜事网',
            // );
            foreach ($arr as $key => $value) {
                if ($value == $select) {
                    $selectd = 'round_select_selected';
                } else {
                    $selectd = '';
                }

                if ($value['category'] == $category){
                ?>
                <li product-id=<?php echo $value['id'];?> class="select_ulist_item round_select <?php echo $selectd; ?> "><?php echo $value['name']; ?></li>

            <?php }} ?>
        </ul>
    </div>
    <div class="bottom_fixed_bar">
        <a class="btn">确定</a>
    </div>
</article>

<script src="js/zepto.min.js"></script>
<script src="js/utility/util.js" type="text/javascript"></script>
<script>

    $(function () {

        function ret(){
            if ($.util.param("from") == "meeting") {
                location.href = "<?php echo $this->createUrl("meeting/bill");?>&from=meeting&order_id=<?php echo $order_id;?>";
            }
            else if ($.util.param("from") == "plan") {
                location.href = "<?php echo $this->createUrl("plan/bill");?>";
            }
            else {
                location.href = "<?php echo $this->createUrl("design/bill");?>&from=design&order_id=<?php echo $order_id;?>";
            }
            // if(retval.success){
            //     location.href = "javascript:history.go(-1);location.reload()";
            // }
        }

        //选择选项
        $(".select_ulist li").on("click", function () {
            $(".select_ulist li").removeClass("round_select_selected");
            $(this).addClass("round_select_selected");
        });


        //点击确认
        $(".btn").on("click", function (){

            if ($(".round_select_selected").attr("product-id") != <?php echo $select['id'] ;?>){//选择有变化时进行post

                var select_id = $(".round_select_selected").attr("product-id");
                
                // if ($(".round_select_selected").attr("product-id") == 0 ;?>){//选择有变化且最后是“无”，进行删除

                var initial_id = <?php echo $select['id'] ;?>;

                // }

                var get_info = {
                    'account_id' : 1,
                    'order_id' : <?php echo $order_id ;?> ,
                    'select_id' : select_id,         
                    'actual_price' : 0,
                    'unit' : 1,
                    'actual_unit_cost' : 0,
                    'actual_service_ratio' : 0,
                    'initial_id' : initial_id
                };

                // alert(initial_id);

                // console.log(get_info);
                $.post('<?php echo $this->createUrl("plan/savechannel")?>' , get_info , function(){
                    ret();         
                })
            }          
        });

        //点击新增
        $("#insert").on("click",function(){
            location.href = "<?php echo $this->createUrl("plan/channel_insert")?>&order_id=<?php echo $_GET['order_id']?>"; 
        });
    
        //点击返回
        $(".l_btn").on("click", function () {
            // location.href = "javascript:history.go(-1);location.reload()";
            ret();
        });
    })
    
</script>

</body>
</html>
