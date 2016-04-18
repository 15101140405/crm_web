<!DOCTYPE html>
<html>

<head>

  <meta charset="UTF-8">

  <title>CSS3垂直手风琴折叠菜单 - jQuery插件库 www.jq22.com</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Iconos -->
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/lc_switch.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/base.css">
	<script src="js/zepto.min.js"></script>
	<script src='js/jquery.js'></script>
    <script src="js/index.js"></script>
    <script src="js/lc_switch.js" type="text/javascript"></script>
<style>
* {
	margin: 0;
	padding: 0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}

body {
	background: #2d2c41;
	font-family: 'Open Sans', Arial, Helvetica, Sans-serif, Verdana, Tahoma;
}

ul {
	list-style-type: none;
}

a {
	color: #b63b4d;
	text-decoration: none;
}

/** =======================
 * Contenedor Principal
 ===========================*/
h1 {
 	color: #FFF;
 	font-size: 24px;
 	font-weight: 400;
 	text-align: center;
 	margin-top: 80px;
 }

h1 a {
 	color: #c12c42;
 	font-size: 16px;
 }

 .accordion {
 	width: 100%;
 	max-width: 360px;
 	margin: 30px auto 20px;
 	background: #FFF;
 	-webkit-border-radius: 4px;
 	-moz-border-radius: 4px;
 	border-radius: 4px;
 }

.accordion .link {
	cursor: pointer;
	display: block;
	padding: 15px 15px 15px 42px;
	color: #4D4D4D;
	font-size: 14px;
	font-weight: 700;
	border-bottom: 1px solid #CCC;
	position: relative;
	-webkit-transition: all 0.4s ease;
	-o-transition: all 0.4s ease;
	transition: all 0.4s ease;
}

.accordion li:last-child .link {
	border-bottom: 0;
}

.accordion li i {
	position: absolute;
	top: 16px;
	left: 12px;
	font-size: 18px;
	color: #595959;
	-webkit-transition: all 0.4s ease;
	-o-transition: all 0.4s ease;
	transition: all 0.4s ease;
}

.accordion li i.fa-chevron-down {
	right: 12px;
	left: auto;
	font-size: 16px;
}

.accordion li.open .link {
	color: #b63b4d;
}

.accordion li.open i {
	color: #b63b4d;
}
.accordion li.open i.fa-chevron-down {
	-webkit-transform: rotate(180deg);
	-ms-transform: rotate(180deg);
	-o-transform: rotate(180deg);
	transform: rotate(180deg);
}

/**
 * Submenu
 -----------------------------*/
 .submenu {
 	display: none;
 	background: #444359;
 	font-size: 14px;
 }

 .submenu li {
 	border-bottom: 1px solid #4b4a5e;
 }

 .submenu a {
 	display: block;
 	text-decoration: none;
 	color: #d9d9d9;
 	padding-right: 12px;
 	padding-left: 42px;
 	-webkit-transition: all 0.25s ease;
 	-o-transition: all 0.25s ease;
 	transition: all 0.25s ease;
 }

 .submenu a:hover {
 	background: #b63b4d;
 	color: #FFF;
 }
</style>
</head>

<body>
	<!-- Contenedor -->
<?php if($type == "has"){?>
	<ul id="accordion" class="accordion">
		<li>
			<div class="link" style="background-color: rgba(99, 93, 210, 0.82);color: #fff;"><i class="fa fa-paint-brush"></i><?php echo $first_team_data['team_name']?><i class="fa fa-chevron-down"></i></div>
			<ul class="submenu">
	<?php foreach ($first_team_data['team_member'] as $key1 => $value1) {?>
				<li style="height:3rem;line-height:3rem;">
					<div class="lcs_wrap" style="display:inline-block">
						<input type="checkbox" service-person-id="<?php echo $value1['id']?>" name="check-3" value="6" class="lcs_check lcs_tt1" checked="checked" autocomplete="off">
						<div class="lcs_switch  lcs_checkbox_switch lcs_on">
							<div class="lcs_cursor">
							</div>
							<div class="lcs_label lcs_label_on">常用</div>
							<div class="lcs_label lcs_label_off">不常用</div>
						</div>
					</div>
					<div style="display:inline-block;color:#fff"><?php echo $value1['name']?>    <span class="t_green" style="font-size:10px;display:inline-block;margin-left:20px;">[<?php echo $value1['price']?>元]起</span></div>
					<a href="<?php echo $this->createUrl("service/index");?>&code=&service_team_id=&from=team_list&service_person_id=<?php echo $value1['id']?>" style="display:inline-block;float:right">查看档期</a>
				</li>
	<?php }?>
			</ul>
		</li>
<?php foreach ($team_data as $key => $value) {?>
		<li>
			<div class="link"><i class="fa fa-paint-brush"></i><?php echo $value['team_name']?><i class="fa fa-chevron-down"></i></div>
			<ul class="submenu">
	<?php foreach ($value['team_member'] as $key1 => $value1) {?>
				<li style="height:3rem;line-height:3rem;">
					<div class="lcs_wrap" style="display:inline-block">
						<input type="checkbox" service-person-id="<?php echo $value1['id']?>" name="check-3" value="6" class="lcs_check lcs_tt1" checked="checked" autocomplete="off">
						<div class="lcs_switch  lcs_checkbox_switch lcs_on">
							<div class="lcs_cursor">
							</div>
							<div class="lcs_label lcs_label_on">常用</div>
							<div class="lcs_label lcs_label_off">不常用</div>
						</div>
					</div>
					<div style="display:inline-block;color:#fff"><?php echo $value1['name']?>    <span class="t_green" style="font-size:10px;display:inline-block;margin-left:20px;">[<?php echo $value1['price']?>元]起</span></div>
					<a href="<?php echo $this->createUrl("service/index");?>&code=&service_team_id=&from=team_list&service_person_id=<?php echo $value1['id']?>" style="display:inline-block;float:right">查看档期</a>
				</li>
	<?php }?>
			</ul>
		</li>
<?php }?>
	</ul>
<?php }else{?>
	<h1>很抱歉，没有更多服务团队了</h1>
<?php }?>
<script type="text/javascript">

$(document).ready(function(e) {
	$('.switch').lc_switch();
    // triggered each time a field changes status
    $('body').delegate('.lcs_check', 'lcs-statuschange', function() {
    var status = ($(this).is(':checked')) ? 'unchecked' : 'checked';
    console.log('field changed status: '+ status );
    });

    // triggered each time a field is checked
    $('body').delegate('.lcs_check', 'lcs-on', function() {
        console.log('field is checked');
        $.post('<?php echo $this->createUrl("service/InsertSupplier");?>',{service_person_id:$(this).attr("service-person-id"),account_id:"<?php echo $_SESSION['account_id']?>"},function(){
            
        });
    });

    // triggered each time a is unchecked
    $('body').delegate('.lcs_check', 'lcs-off', function(){
        console.log('field is unchecked');
        $.post('<?php echo $this->createUrl("service/DelSupplier");?>',{service_person_id:$(this).attr("service-person-id"),account_id:"<?php echo $_SESSION['account_id']?>"},function(){
            
        });
    });   

	//订单状态按钮，初始渲染
	$(".lcs_checkbox_switch").removeClass('lcs_on');
	$(".lcs_checkbox_switch").addClass('lcs_off');
	<?php foreach ($service_person_id as $key => $value) {?>
        $("[service-person-id='<?php echo $value?>']").next().removeClass('lcs_off');
        $("[service-person-id='<?php echo $value?>']").next().addClass('lcs_on');
    <?php }?>
})
</script> 
</body>
</html>
