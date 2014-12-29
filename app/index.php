<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/../framework/yii.php';

if($_SERVER['SERVER_NAME']=="localhost") {                          // LOCALHOST    
    $config=dirname(__FILE__).'/protected/config/main.php';    
    defined('YII_DEBUG') or define('YII_DEBUG',true);    
} elseif($_SERVER['SERVER_NAME']=="crm.staging.eatads.com") {
    $config=dirname(__FILE__).'/protected/config/main_stag.php';    
    defined('YII_DEBUG') or define('YII_DEBUG',false);
} elseif($_SERVER['SERVER_NAME']=="crm.eatads.com") {
    $config=dirname(__FILE__).'/protected/config/main_live.php';    
    defined('YII_DEBUG') or define('YII_DEBUG',true);
} else {
    $config=dirname(__FILE__).'/protected/config/main.php';    
    defined('YII_DEBUG') or define('YII_DEBUG',false);
}

// changed a bit
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

date_default_timezone_set('Asia/Kolkata');

require_once($yii);
Yii::createWebApplication($config)->run();
