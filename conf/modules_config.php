<?php

return array(
    // uncomment the following to enable the Gii tool
    'gii' => array(
        'class' => 'system.gii.GiiModule',
        'password' => '123456',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
        'ipFilters' => array('127.0.0.1', '::1'),
        'generatorPaths' => array(
            'bootstrap.gii',
        ),
    ),

    //Yii auth
    'authority' => array(
        'class' => 'auth.AuthModule',
        'strictMode' => true, // when enabled authorization items cannot be assigned children of the same type.
        'userClass' => 'AdUser', // the name of the user model class.
        'userIdColumn' => 'user_id', // the name of the user id column.
        'userNameColumn' => 'user_email', // the name of the user name column.
        'defaultLayout' => 'application.views.layouts.ad.main', // the layout used by the module.
        'viewDir' => null, // the path to view files to use with this module.
    ),

);