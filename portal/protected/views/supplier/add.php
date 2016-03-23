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
</head>
<body>
<article>
    <div class="tool_bar">
        <div class="l_btn" data-icon="&#xe679;"></div>
        <h2 class="page_title">新增供应商</h2>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <li class="int_ulist_item list_more" id="supplier_type">
                <span>供应商类别</span>
                <div class="align_r supplier_type"></div>
            </li>
            <li class="int_ulist_item" id="name">
                <span class="label">姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="输入姓名"/>
                </div>
            </li>
            <li class="int_ulist_item phone" id="phone">
                <span class="label">联系电话</span>
                <div class="int_bar">
                    <input class="align_r" type="text" placeholder="输入联系电话"/>
                </div>
            </li>
            <li class="int_ulist_item list_more" id="supplier_contract">
                <span class="label contract">供应商合同</span>
                <input type="file" capture="camera"/>
                <div class="align_r supplier_contract"></div>
            </li>
        </ul>
    </div>
    <div class="btn" id="add">确 定</div>
    <div class="btn del hid" id="del">删 除</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        var choosetype = unescape($.util.param("choose_type")); //选择供应商类别后返回的参数
        var choosetypeid = unescape($.util.param("choose_type_id")); //选择供应商类别后返回的参数的id
        var supplier_id = unescape($.util.param("supplier_id")); //点击编辑时传来的 供应商id

        //判断是新增，或者 编辑
        if (supplier_id != "null") {// 编辑某个供应商
            if (choosetype == "null") { // 从前页面编辑进入
                $(".page_title").html("编辑供应商");
                //background data
                //获取此供应商信息
                $.getJSON("<?php echo $this->createUrl('supplier/edit');?>",{supplier_id: supplier_id},function(retval){
                    if(retval.success){
                        //后台获取到 用户头像，用户姓名，电话，职位，部门
                        //var retval = {na: 'na', phone: 'phone', supplier_type: 'zhuchiren'};
                        $("#name input").val(retval.na);
                        $("#phone input").val(retval.phone);
                        $("#supplier_type div").html(retval.supplier_type);
                        choosetypeid = retval.supplier_type_id;
                        //如果合同的值不为空，则在合同处写上“已上传”
                        if (retval.supplier_contract != "") {
                            $("#supplier_contract div").html("已上传");
                        }
                        $("#del").removeClass("hid");
                    }else{
                        alert('太累了，歇一歇，一秒后再试试！');
                        return false;
                    }
                });
            } else {//从供应商类别 choose_supplier_type 进入
                var s_info = $.parseJSON(localStorage.getItem("s_info"));
                console.log(s_info);

                $("#name input").val(s_info.na);
                $("#phone input").val(s_info.phone);
                $("#supplier_type div").html(choosetype);
                if (s_info.supplier_contract != "") {
                    $("#supplier_contract div").html("已上传");
                }
                $("#del").removeClass("hid");
            }
        } else if (choosetype != "null") {//如果新增，且从 供应商选择 进入
            var s_info = $.parseJSON(localStorage.getItem("s_info"));
            console.log(s_info);

            $("#name input").val(s_info.na);
            $("#phone input").val(s_info.phone);
            $("#supplier_type div").html(choosetype);
            if (s_info.supplier_contract != "") {
                $("#supplier_contract div").html("已上传");
            }
        }

        //选择供应商类别
        $("#supplier_type").on("click", function () {
            var s_info = get_supplier_info();
            localStorage.setItem("s_info", JSON.stringify(s_info));
            if (supplier_id != "null")
                location.href = "<?php echo $this->createUrl("supplier/chooseType");?>&supplier_id=" + supplier_id;
            else
                location.href = "<?php echo $this->createUrl("supplier/chooseType");?>";
        })

        //点击确认按钮提交用户信息

        $("#add").on("click", function () {
            var s_info = get_supplier_info();
            //判断信息是否完整
            //if (s_info.na == "" || s_info.phone == "" || s_info.supplier_contract == "" || s_info.supplier_type == "") {
            if (s_info.na == "" || s_info.phone == "" ||  s_info.supplier_type == "") {
                alert("请补全信息");
                return false;
            }
            else if (!$.regex.isPhone(s_info.phone)) { //phone number correct or not
                alert("请输入正确的手机号！");
                return false;
            }
            //background data
            //提交用户信息
            $.post('<?php echo $this->createUrl("supplier/save");?>&supplier_id='+supplier_id,s_info,function(retval){
                alert("天");
               // if(retval.success){
                location.href = "<?php echo $this->createUrl('supplier/list');?>";
               // }else{
               //   alert('太累了，歇一歇，一秒后再试试！');
                //     return false;
                //   }
            });
        })

        $("#del").on("click", function () {
            //background data
            $.post('<?php echo $this->createUrl('supplier/delete');?>&supplier_id='+supplier_id,{supplier_id:supplier_id},function(retval){
           alert(retval);
            //  if(retval.success){
            alert('删除成功');
            localStorage.clear();
            location.href = '<?php echo $this->createUrl("supplier/list");?>';
            //  }else{
            //    alert("删除失败");
            //  }
             })
        });

        //点击返回按钮
        $(".l_btn").on("click", function () {
            localStorage.clear();
            location.href = '<?php echo $this->createUrl("supplier/list");?>';
        });


        /* ===========================
         * 获取界面填写的供应商信息
         * =========================== */
        //后台自动生成一个supplier_id
        function get_supplier_info() {
            var s_info = {
                na: $("#name input").val(),
                phone: $("#phone input").val(),
                supplier_contract: "",
                supplier_type: $("＃supplier_type div").html(),
                supplier_id:supplier_id,
                supplier_type_id:choosetypeid
            }
            return s_info;
        }
    });
</script>
</body>
</html>

