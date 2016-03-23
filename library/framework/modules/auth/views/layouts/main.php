
<?php /* @var $this AuthController */ ?>

<?php $this->beginContent($this->module->appLayout); ?>

<div class="auth-module">

    <div class="row">
        <div class="span2" style="margin-left:5px;">
            <?php $this->widget('bootstrap.widgets.TbMenu', array(
                'type' => 'pills',
                'stacked'=>true, // whether this is a stacked menu
                'items' => array(
                    array(
                        'label' => Yii::t('AuthModule.main', 'Assignments'),
                        'url' => array('/auth/assignment/index'),
                        'active' => $this instanceof AssignmentController,
                    ),
                    array(
                        'label' => $this->capitalize($this->getItemTypeText(CAuthItem::TYPE_ROLE, true)),
                        'url' => array('/auth/role/index'),
                        'active' => $this instanceof RoleController,
                    ),
                    array(
                        'label' => $this->capitalize($this->getItemTypeText(CAuthItem::TYPE_TASK, true)),
                        'url' => array('/auth/task/index'),
                        'active' => $this instanceof TaskController,
                    ),
                    array(
                        'label' => $this->capitalize($this->getItemTypeText(CAuthItem::TYPE_OPERATION, true)),
                        'url' => array('/auth/operation/index'),
                        'active' => $this instanceof OperationController,
                    ),
                ),
            ));?>
        </div>

        <div class="span10">
            <?php echo $content; ?>
        </div>
    </div>

</div>

<?php $this->endContent(); ?>