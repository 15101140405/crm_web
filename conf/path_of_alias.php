<?php

Yii::setPathOfAlias('common', $WEB_BASE_DIR . '/common');
Yii::setPathOfAlias('framework', $LIBRARY_DIR . '/framework');
Yii::setPathOfAlias('extensions', $LIBRARY_DIR . '/framework/extensions');

Yii::setPathOfAlias('auth', $LIBRARY_DIR . '/framework/modules/auth');
Yii::setPathOfAlias('plugins', $LIBRARY_DIR . '/plugins');
Yii::setPathOfAlias('bootstrap', $LIBRARY_DIR . '/framework/extensions/bootstrap');
Yii::setPathOfAlias('xupload', $LIBRARY_DIR . '/framework/extensions/xupload');
Yii::setPathOfAlias('editable', $LIBRARY_DIR . '/framework/extensions/editable');