<?php
$this->breadcrumbs = array(
    'Logs' => array('index'),
    $model->id,
);

$this->menu = array(
    array('label' => 'List Log', 'url' => array('index')),
    array('label' => 'Create Log', 'url' => array('create')),
    array('label' => 'Update Log', 'url' => array('update', 'id' => $model->id)),
    array('label' => 'Delete Log', 'url' => '#', 'linkOptions' => array('submit' => array('delete', 'id' => $model->id), 'confirm' => 'Are you sure you want to delete this item?')),
    array('label' => 'Manage Log', 'url' => array('admin')),
);
?>

<h1>View Log #<?php echo $model->id; ?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
    'data' => $model,
    'attributes' => array(
        'id',
        'action',
        'info',
        'user',
        'ip',
        'create_time',
    ),
)); ?>
