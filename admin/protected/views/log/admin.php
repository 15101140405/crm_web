<?php $this->widget('bootstrap.widgets.TbBreadcrumbs', array(
    'homeLink' => CHtml::link('首页', Yii::app()->homeUrl),
    'links' => array('日志列表'),
)); ?>

<div class="panel panel-info">
    <div class="panel-heading"><h3 class="panel-title"><i class="icon-folder-open"></i> 日志列表</h3></div>
    <div class="panel-body">

        * 可使用表格第一行输入框进行检索，点击表格列名称文字可排序。
        <?php $this->widget('bootstrap.widgets.TbGridView', array(
            'id' => 'log-admin-grid',
            'type' => 'hover striped condensed',
            'dataProvider' => $model->search(),
            'filter' => $model,
            'columns' => array(
                array(
                    'name' => 'id',
                    'htmlOptions' => array(
                        'width' => "7%",
                    ),
                ),
                array(
                    'name' => 'module',
                    'htmlOptions' => array(
                        'width' => "6%",
                    ),
                ),
                array(
                    'name' => 'action',
                    'htmlOptions' => array(
                        'width' => "12%",
                    ),
                ),
                array(
                    'name' => 'level',
                    'htmlOptions' => array(
                        'width' => "8%",
                    ),
                ),
                array(
                    'name' => 'info',
                    'htmlOptions' => array(
                        'width' => "35%",
                    ),
                ),
                array(
                    'name' => 'user',
                    'htmlOptions' => array(
                        'width' => "8%",
                    ),
                ),
                array(
                    'name' => 'ip',
                    'htmlOptions' => array(
                        'width' => "9%",
                    ),
                ),
                array(
                    'name' => 'create_time',
                    'htmlOptions' => array(
                        'width' => "12%",
                    ),
                ),
            ),

            'pager' => array(
                'header' => '',
                'firstPageLabel' => '首页',
                'lastPageLabel' => '尾页',
                'prevPageLabel' => '上一页',
                'nextPageLabel' => '下一页',
            ),

            'summaryText' => '共 {count} 条 / {pages}页， 当前 {start}-{end} 条',

        )); ?>

    </div>
</div>