<?php

$APP_NAME = current(array_slice(explode(DIRECTORY_SEPARATOR, dirname(__FILE__)), -3));

// base config of the website
$ENV = constant('ENV');
$APP_ID = constant('APP_ID');
$WEB_BASE_DIR = constant('WEB_BASE_DIR');
$LIBRARY_DIR = constant('LIBRARY_DIR');

// uncomment the following to define a path alias
require_once($WEB_BASE_DIR . '/conf/path_of_alias.php');

$import_config = require_once($WEB_BASE_DIR . '/conf/import_config.php');
$components_config = require_once($WEB_BASE_DIR . '/conf/components_config.php');
$modules_config = require_once($WEB_BASE_DIR . '/conf/modules_config.php');

// merge params config
$base_config = require($WEB_BASE_DIR . '/conf/base_config.php');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => $APP_NAME,

    'timeZone' => 'Asia/Chongqing',
    'language' => 'zh_cn',

    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => $import_config,
    'modules' => $modules_config,

    // application components
    'components' => array(
        'user' => $components_config['user'],
        'authManager' => $components_config['authManager'],
        'urlManager' => $components_config['urlManager'],
        'db' => $base_config['db_' . $ENV],
        'errorHandler' => $components_config['errorHandler'],
        'log' => $components_config['log'],
        'bootstrap' => $components_config['bootstrap'],
        'editable' => $components_config['editable'],
    ),

    'params' => $base_config,
);