<?php
$this->pageTitle = Yii::app()->name . ' - Error';
$this->breadcrumbs = array(
    'Error',
);
?>

<div class='text-center'>
    <div class="hero-unit">
        <h1>Error <?php echo $code; ?></h1>

        <h3><?php echo CHtml::encode($message); ?></h3>

        <p>
            <a class="btn btn-primary btn-large" href="javascript :;" onClick="javascript :history.back(-1);">返回</a>
        </p>
    </div>
</div>