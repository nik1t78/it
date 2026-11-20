<?php

// change the following paths if necessary
//$yii=dirname(__FILE__).'/../../framework/yii.php';
$yii=dirname(__FILE__).'/../framework/YiiBase.php';
$config=dirname(__FILE__).'/protected/config/main.php';


// remove the following lines when in production mode
 defined('YII_DEBUG') or define('YII_DEBUG',false);
// specify how many levels of call stack should be shown in each log message
 defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
 // defined('YII_ENV') or define('YII_ENV', 'prod');
 define('YII_ENABLE_ERROR_HANDLER', false);
 define('YII_ENABLE_EXCEPTION_HANDLER', true);

require_once($yii);

class Yii extends YiiBase
{
    /**
     * @static
     * @return CWebApplication
     */
    public static function app()
    {
        return parent::app();
    }
}

Yii::createWebApplication($config);
$ver = Yii::app()->request->getParam('version');
$ver = urlencode($ver);
if (!empty($ver)){
    setcookie('version', $ver, time()+3600, '/', 'usp.admlr.lipetsk.ru');
} elseif (!empty($_COOKIE['version']) && empty($ver)) {
    $ver = $_COOKIE['version'];
} elseif (empty($_COOKIE['version']) && empty($ver)) {
    $ver = 'original';
}

if ($ver == 'special') {
    Yii::app()->setTheme('special');
} else {
    Yii::app()->setTheme('front');
}
Yii::app()->run();
