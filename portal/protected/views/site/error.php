<?php
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>


<div class='row text-center'>
	<div class="hero-unit">
	  <h1>Error <?php echo $code; ?></h1>
	  <h3><?php echo CHtml::encode($message); ?></h3>
	  <p>
	    <a class="btn btn-primary btn-large" href="<?php echo $this->createUrl('site/index');?>">
	      返回首页
	    </a>
	  </p>
	</div>
</div>