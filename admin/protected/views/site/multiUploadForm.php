<?php
    $id = 'uploadForm'.mt_rand(10000, 99999);
    $this->widget('xupload.XUpload', array(
        // 添加随机数后缀防止一个页面多上传表单重复id
        'htmlOptions' => array('id'=>$id, 'class'=>'uploadForm'),
        'url' => Yii::app()->createUrl("site/upload"),
        'model' => $uploadForm,
        'attribute' => 'file',
        'multiple' => true,
        'autoUpload' => true,
    ));

    Yii::app()->clientScript->registerScript($id, '$("#'.$id.'").bind("fileuploaddone", function (e, data) {     var iframe; parent.$("iframe").each(function() {    if(this.contentWindow === window) iframe = this;    });  var id = iframe.id;var func = iframe.getAttribute("data-func"); if(typeof(window.parent[func]) == "function"){window.parent[func](id, data.result)};      });');
?>


