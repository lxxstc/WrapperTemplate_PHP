<?php
// change the following paths if necessary
date_default_timezone_set("PRC"); 
header("X-Powered-By:qunar qunar.com");
$yii=dirname(__FILE__).'/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG', true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
require_once($yii);
Yii::createWebApplication($config)->run();
