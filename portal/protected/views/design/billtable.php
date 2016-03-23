<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>报价单</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
</head>
<body>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>
<style type="text/css">
.tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #ebab3a;border-collapse: collapse;}
.tftable th {font-size:12px;background-color:#e6983b;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;text-align:left;}
.tftable tr {background-color:#ffffff;}
.tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #ebab3a;}
.tftable tr:hover {background-color:#ffff99;}
</style>

<table class="tftable" border="1">
<tr><th colspan="3">基本信息</th></tr>
<tr><td width="10%">订单编号</td><td colspan="2" width="90%"><?php echo $order_data['id']?></td></tr>
<tr><td width="10%">新娘信息</td><td width="50%"><?php echo $order_data['groom_name']?></td><td width="40%"><?php echo $order_data['groom_phone']?></td></tr>
<tr><td width="10%">新娘信息</td><td width="50%"><?php echo $order_data['bride_name']?></td><td width="40%"><?php echo $order_data['bride_phone']?></td></tr>
<tr><td width="10%">策划师</td><td colspan="2" width="90%"><?php echo $order_data['designer_name']?></td></tr>
<tr><td width="10%">婚宴折扣</td><td colspan="2" width="90%"><?php echo $order_data['feast_discount']?></td></tr>
<tr><td width="10%">婚礼折扣</td><td colspan="2" width="90%"><?php echo $order_data['other_discount']?></td></tr>
<tr><td width="10%">抹零</td><td colspan="2" width="90%"><?php echo $order_data['cut_price']?></td></tr>
<tr><td width="10%">订单总价</td><td colspan="2" width="90%">总价（乘完折扣）</td></tr>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<!-- 婚宴 -->
<?php 
	if (!empty($arr_wed_feast)) {
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<tr><td width="10%" rowspan = "5">婚宴</td><td width="4%">1</td><td width="12%"><?php echo $arr_wed_feast['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $arr_wed_feast['table_num']; ?></td><td width="9%"><?php echo $arr_wed_feast['unit']; ?></td><td width="18%"><?php echo $arr_wed_feast['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 
	};
?>

<!-- 灯光 -->
<?php
    if (!empty($arr_light)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_light as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $light[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_light);?>">灯光</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $light['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $light['amount']; ?></td><td width="4%"><?php echo $light['unit']; ?></td><td width="23%"><?php echo $light['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $light['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $light['amount']; ?></td><td width="4%"><?php echo $light['unit']; ?></td><td width="23%"><?php echo $light['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 视频 -->
<?php
    if (!empty($arr_video)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_video as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $video[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_video);?>">视频</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $video['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $video['amount']; ?></td><td width="4%"><?php echo $video['unit']; ?></td><td width="23%"><?php echo $video['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $video['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $video['amount']; ?></td><td width="4%"><?php echo $video['unit']; ?></td><td width="23%"><?php echo $video['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 主持人 -->
<?php
    if (!empty($arr_host)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_host as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $host[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_host);?>">主持人</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $host['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $host['amount']; ?></td><td width="4%"><?php echo $host['unit']; ?></td><td width="23%"><?php echo $host['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $host['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $host['amount']; ?></td><td width="4%"><?php echo $host['unit']; ?></td><td width="23%"><?php echo $host['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 摄像 -->
<?php
    if (!empty($arr_camera)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_camera as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $camera[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_camera);?>">摄像</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $camera['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $camera['amount']; ?></td><td width="4%"><?php echo $camera['unit']; ?></td><td width="23%"><?php echo $camera['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $camera['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $camera['amount']; ?></td><td width="4%"><?php echo $camera['unit']; ?></td><td width="23%"><?php echo $camera['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 摄影 -->
<?php
    if (!empty($arr_photo)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_photo as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $photo[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_photo);?>">摄影</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $photo['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $photo['amount']; ?></td><td width="4%"><?php echo $photo['unit']; ?></td><td width="23%"><?php echo $photo['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $photo['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $photo['amount']; ?></td><td width="4%"><?php echo $photo['unit']; ?></td><td width="23%"><?php echo $photo['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 化妆 -->
<?php
    if (!empty($arr_makeup)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_makeup as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $makeup[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_makeup);?>">化妆</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $makeup['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $makeup['amount']; ?></td><td width="4%"><?php echo $makeup['unit']; ?></td><td width="23%"><?php echo $makeup['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $makeup['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $makeup['amount']; ?></td><td width="4%"><?php echo $makeup['unit']; ?></td><td width="23%"><?php echo $makeup['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 其他 -->
<?php
    if (!empty($arr_other)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_other as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $other[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_other);?>">其他</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $other['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $other['amount']; ?></td><td width="4%"><?php echo $other['unit']; ?></td><td width="23%"><?php echo $other['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $other['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $other['amount']; ?></td><td width="4%"><?php echo $other['unit']; ?></td><td width="23%"><?php echo $other['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 场地布置 -->
<?php
    if (!empty($arr_decoration)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_decoration as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $decoration[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_decoration);?>">场地布置</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $decoration['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $decoration['amount']; ?></td><td width="4%"><?php echo $decoration['unit']; ?></td><td width="23%"><?php echo $decoration['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $decoration['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $decoration['amount']; ?></td><td width="4%"><?php echo $decoration['unit']; ?></td><td width="23%"><?php echo $decoration['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 平面设计 -->
<?php
    if (!empty($arr_graphic)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_graphic as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $graphic[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_graphic);?>">平面设计</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $graphic['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $graphic['amount']; ?></td><td width="4%"><?php echo $graphic['unit']; ?></td><td width="23%"><?php echo $graphic['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $graphic['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $graphic['amount']; ?></td><td width="4%"><?php echo $graphic['unit']; ?></td><td width="23%"><?php echo $graphic['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 视频设计 -->
<?php
    if (!empty($arr_film)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_film as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $film[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_film);?>">视频设计</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $film['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $film['amount']; ?></td><td width="4%"><?php echo $film['unit']; ?></td><td width="23%"><?php echo $film['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $film['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $film['amount']; ?></td><td width="4%"><?php echo $film['unit']; ?></td><td width="23%"><?php echo $film['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

<!-- 策划费&杂费 -->
<?php
    if (!empty($arr_designer)) {
    $i=1;
?>
<table class="tftable" border="1">
<tr><th>产品类别</th><th>序号</th><th>产品名称</th><th>质量标准</th><th>数量</th><th>单位</th><th>单价</th><th>示意图</th></tr>
<?php
	    foreach ($arr_designer as $key => $value) {
	        foreach ($value as $key1 => $value1) {
	            $designer[$key1] = $value1;
	    }
?>
<?php 
		if($i==1){
?>
<tr><td width="10%" rowspan = "<?php echo count($arr_designer);?>">策划费&杂费</td><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $designer['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $designer['amount']; ?></td><td width="4%"><?php echo $designer['unit']; ?></td><td width="23%"><?php echo $designer['unit_price']; ?></td><td width="23%">Row:1 Cell:8</td></tr>
<?php
		$i++;
		}else{
?>
<tr><td width="4%"><?php echo $i;?></td><td width="12%"><?php echo $designer['name']; ?></td><td width="20%"></td><td width="4%"><?php echo $designer['amount']; ?></td><td width="4%"><?php echo $designer['unit']; ?></td><td width="23%"><?php echo $designer['unit_price']; ?></td><td width="23%">Row:2 Cell:8</td></tr>
<?php   $i++;}};?>
</table>

<p><small>Created with the <a href="http://www.textfixer.com/html/html-table-generator.php" target="_blank">HTML Table Generator</a></small></p>

<?php 	
	};
?>

</body>
</html>