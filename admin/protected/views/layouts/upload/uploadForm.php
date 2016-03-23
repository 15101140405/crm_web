<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <?php
    // 加载bootstrap依赖
    $bootstrap=yii::app()->getComponent('bootstrap');
    $bootstrap->register();
    ?>
    <style type="text/css">
        body{padding: 0 !important;margin: 0 !important;}
    </style>
</head>
<body>
<?php
    // 上传表单内容
    echo $content;
?>
</body>
</html>