<?php

$webBaseDir = realpath(dirname(__FILE__) . "/../");
$baseConfig = require_once($webBaseDir . '/conf/base_config.php');
$env = $baseConfig['env'];
$libraryDir = $webBaseDir . '/library/';

// change the following paths if necessary
$yii = $libraryDir . '/framework/yii/framework/yii.php';
$config = dirname(__FILE__) . '/protected/config/console.php';

if ($env != 'production') {
    defined('YII_DEBUG') or define('YII_DEBUG', true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}

defined('ENV') or define('ENV', $env);
defined('APP_ID') or define('APP_ID', $baseConfig['appId']);
defined('WEB_BASE_DIR') or define('WEB_BASE_DIR', $webBaseDir);
defined('LIBRARY_DIR') or define('LIBRARY_DIR', $libraryDir);

// let's start
require_once($yii);
Yii::createConsoleApplication($config)->run();