<?php

$LOG_BASE_DIR = $WEB_BASE_DIR . '/log';

return array(

    //X-editable config
    'editable' => array(
        'class' => 'editable.EditableConfig',
        'form' => 'bootstrap',
        'mode' => 'popup',
        'defaults' => array(
            'emptytext' => '点击以编辑',
            //'ajaxOptions' => array('dataType' => 'json') //usefull for json exchange with server
        )
    ),

    'bootstrap' => array(
        'class' => 'extensions.bootstrap.components.Bootstrap',
    ),

    'authManager' => array(
        'class' => 'auth.components.CachedDbAuthManager',
        'cachingDuration' => 3600,
        'behaviors' => array(
            'auth' => array(
                'class' => 'auth.components.AuthBehavior',
                'admins' => array('admin', '沐潇', '凌丘'),
            ),
        ),
    ),

    'user' => array(
        // enable cookie-based authentication
        'allowAutoLogin' => true,
        'class' => 'auth.components.AuthWebUser',
    ),

    // uncomment the following to enable URLs in path-format
    'urlManager' => array(
        'urlFormat' => 'path',
        'rules' => array(
            '<controller:\w+>/<id:\d+>' => '<controller>/view',
            '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
        ),
        'showScriptName' => false,
    ),

    'errorHandler' => array(
        // use 'site/error' action to display errors
        'errorAction' => 'site/error',
    ),

    'log' => array(
        'class' => 'CLogRouter',
        'routes' => array(
            // array(
            // 'class'=>'CWebLogRoute',
            // 'levels'=>'profile, trace, info, error, warning',
            // ),
            array(
                'class' => 'CProfileLogRoute',
                'levels' => 'profile',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'error, warning',
                'categories' => 'system',
                'logPath' => $LOG_BASE_DIR,
                'logFile' => $APP_ID . "." . $APP_NAME . '.system.wf_' . constant('ENV') . '.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'profile,trace,error',
                'categories' => 'application',
                'logPath' => $LOG_BASE_DIR,
                'logFile' => $APP_ID . "." . $APP_NAME . '.wf_' . constant('ENV') . '.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'info',
                'categories' => 'application',
                'logPath' => $LOG_BASE_DIR,
                'logFile' => $APP_ID . "." . $APP_NAME . '.info_' . constant('ENV') . '.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'debug, trace',
                'categories' => 'application.*',
                'logPath' => $LOG_BASE_DIR,
                'logFile' => $APP_ID . "." . $APP_NAME . '.debug_' . constant('ENV') . '.log',
            ),
            array(
                'class' => 'CFileLogRoute',
                'levels' => 'debug, trace',
                'categories' => 'dto',
                'logPath' => $LOG_BASE_DIR,
                'logFile' => $APP_ID . "." . $APP_NAME . '.dto_' . constant('ENV') . '.log',
            ),
        ),
    ),

);
