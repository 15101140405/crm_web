<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo Yii::app()->name; ?></title>

    <?php
    // css
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/classic/index.css");
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/css/classic/style.css");
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/../library/plugins/bootstrap3/css/bootstrap.min.css");
    Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . "/../library/plugins/sco/css/scojs.css");

    // js
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/jquery/jquery-1.10.2.min.js");
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/bootstrap3/js/bootstrap.min.js");
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/../library/plugins/sco/js/sco.message.js");
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/siteIndex.js");
    ?>

</head>
<body>
<div class="container">
    <div class="row logo"><h1>CRM | 婚庆酒店</h1></div>

    <div class="row">
        <form class="form-inline col-lg-offset-2" role="form">
            <div class="form-job col-lg-4">
                <label class="sr-only" for="userName">用户名</label>
                <input type="text" class="form-control input-lg" id="username" placeholder="用户名" value="">
            </div>
            <div class="form-job col-lg-4">
                <label class="sr-only" for="password">密码</label>
                <input type="password" class="form-control input-lg" id="password" placeholder="密码" value="">
            </div>

            <a href="javascript:;" class="btn btn-info btn-lg col-lg-1" id="loginButton"
               data-url="<?php echo $this->createUrl("site/login") ?>">登录</a>

            <div class="clearfix"></div>
        </form>

    </div>
</div>
<!-- end of container -->

</body>
</html>