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
        <!-- <div class="l_btn" data-icon="&#xe679;"></div> -->
        <h2 class="page_title">新增供应商</h2>
    </div>
    <div class="int_ulist_module">
        <ul class="int_ulist">
            <!-- <li class="int_ulist_item list_more" id="supplier_type">
                <span>供应商类别</span>
                <div class="align_r supplier_type"></div>
            </li> -->
<?php if(!empty($staff)){ ?>
            <li class="int_ulist_item" id="name">
                <span class="label">姓名</span>
                <div class="int_bar">
                    <input class="align_r" type="text" value="<?php echo $staff['name']?>" placeholder="输入姓名"/>
                </div>
            </li>
            <li class="int_ulist_item phone" id="phone">
                <span class="label">联系电话</span>
                <div class="int_bar">
                    <input class="align_r" type="text" value="<?php echo $staff['telephone']?>" placeholder="输入联系电话"/>
                </div>
            </li>
<?php }else{ ?>
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
<?php } ?>
            <!-- <li class="int_ulist_item list_more" id="supplier_contract">
                <span class="label contract">供应商合同</span>
                <input type="file" capture="camera"/>
                <div class="align_r supplier_contract"></div>
            </li> -->
        </ul>
    </div>
    <div class="btn" id="add">确 定</div>
    <div class="btn" id="edit">确 定</div>
    <div class="btn del" id="del">删 除</div>
</article>
<script src="js/zepto.min.js"></script>
<script src="js/common.js"></script>
<script>
    $(function () {
        //初始渲染
<?php if(!empty($staff)){ ?>
        $("#add").remove();
<?php }else{ ?>
        $("#edit").remove();
        $("#del").remove();
<?php } ?>
        //新增
        $("#add").on("click", function () {
            var s_info = get_supplier_info();

            //判断信息是否完整
            if (s_info.na == "" || s_info.phone == "") {
                alert("请补全信息");
                return false;
            }
            else if (!$.regex.isPhone(s_info.phone)) { //phone number correct or not
                alert("请输入正确的手机号！");
                return false;
            }
            //background data
            console.log(s_info);
            //提交用户信息
            $.post('<?php echo $this->createUrl("supplier/insert");?>',s_info,function(retval){
                location.href = "<?php echo $this->createUrl('supplier/list');?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=<?php echo $_GET['supplier_id']?>&product_id=<?php echo $_GET['product_id']?>";
            });
        })
        //编辑
        $("#edit").on("click", function () {
            var s_info = get_supplier_info();

            //判断信息是否完整
            if (s_info.na == "" || s_info.phone == "") {
                alert("请补全信息");
                return false;
            }
            else if (!$.regex.isPhone(s_info.phone)) { //phone number correct or not
                alert("请输入正确的手机号！");
                return false;
            }
            //background data
            console.log(s_info);
            //提交用户信息
            $.post('<?php echo $this->createUrl("supplier/edit");?>',{supplier_id: '<?php echo $_GET['edit_supplier_id']?>', na: $("#name input").val(), phone: $("#phone input").val()},function(){
                location.href = "<?php echo $this->createUrl('supplier/list');?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=<?php echo $_GET['supplier_id']?>&product_id=<?php echo $_GET['product_id']?>";
            });
        })
        //删除
        $("#del").on("click", function () {
            $.post('<?php echo $this->createUrl('supplier/del');?>',{supplier_id:"<?php echo $_GET['edit_supplier_id']?>"},function(){
                location.href = '<?php echo $this->createUrl('supplier/list');?>&supplier_type=<?php echo $_GET['supplier_type']?>&category=<?php echo $_GET['category']?>&supplier_id=<?php echo $_GET['supplier_id']?>&product_id=<?php echo $_GET['product_id']?>';
            });
        });



        /* ===========================
         * 获取界面填写的供应商信息
         * =========================== */
        //后台自动生成一个supplier_id
        function get_supplier_info() {
            var myDate = new Date();

            var update_time = myDate.getFullYear() + "-" + myDate.getMonth() + "-" + myDate.getDate() + " " + myDate.getHours() + "-" + myDate.getMinutes() + "-" + myDate.getSeconds();
            var s_info = {
                account_id: "<?php echo $_SESSION['account_id']?>",
                na: $("#name input").val(),
                phone: $("#phone input").val(),
                type_id: "<?php echo $_GET['supplier_type']?>",
                update_time: update_time
            }
            return s_info;
        }
    });
</script>
</body>
</html>

