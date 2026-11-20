<?php

/**
 * Получаем массив с установленными модулями
 **/
function getAvailableModules(){
    $modulesList = scandir(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR);
    foreach($modulesList as $key=>$val){
        if($val == '.' || $val == '..' || $val == '.DS_Store')
            unset($modulesList[$key]);
    }
    sort($modulesList);
    $modulesList['gii']=array(
			'class'=>'system.gii.GiiModule',
			'password'=>'111',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','192.168.0.111', '::1'),
		);
    return $modulesList;
}
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'Департамент культуры администрации города Липецка',
        'preload'=>array('log'),
    
        'import' => array(
            
            'application.models.*',
            'application.components.*',
            
            'application.extensions.VarDumper.*',
            'application.extensions.tree.*',
            'application.widgets.*',

            'application.modules.user.UserModule',
            'application.modules.user.models.*',
            'application.modules.user.components.*',
            
            'application.modules.contragent.models.*',
            'application.modules.contragent.components.*',

            'application.modules.backend.controllers.*',
            'application.modules.backend.widgets.*',
            'application.modules.backend.helpers.*',
            'application.modules.backend.models.*',
            'application.modules.backend.components.*',     

            'application.modules.pages.controllers.*',
            'application.modules.pages.models.*',
            'application.modules.pages.components.*', 

            'application.modules.menu.controllers.*',
            'application.modules.menu.models.*',
            'application.modules.menu.components.*',  

            'application.modules.settings.controllers.*',
            'application.modules.settings.models.*',
            'application.modules.settings.components.*',

            'application.modules.filemanager.controllers.*',
            'application.modules.filemanager.models.*',
            'application.modules.filemanager.components.*',  
            'application.modules.filemanager.widgets.*',

            'application.modules.catalog.controllers.*',
            'application.modules.catalog.models.*',
            'application.modules.catalog.components.*',  
            'application.modules.catalog.widgets.*',

            'application.modules.iblock.controllers.*',
            'application.modules.iblock.models.*',
            'application.modules.iblock.components.*',  
            'application.modules.iblock.widgets.*',

            'application.modules.textblock.controllers.*',
            'application.modules.textblock.models.*',
            'application.modules.textblock.components.*',  
            'application.modules.textblock.widgets.*',
        ),
    
	'modules'=>getAvailableModules(),
    
	// application components
	'components'=>array(
            'db' => array(
                //'connectionString' => 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=nlmk_baza',
                'connectionString' => 'mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=culture',
                //'connectionString' => 'mysql:host=localhost;dbname=culture48ru_new',
    		'emulatePrepare' => true,
                'tablePrefix' => 'tbl_',
    		'username' => 'root',
    		'password' => 'root',
                //'username' => 'cultu_new', 
                //'password' => 'whe0shaf',
    		'charset' => 'utf8',
                'enableProfiling' => true, // включаем профайлер
                'enableParamLogging' => true, // показываем значения параметров
            ),
            
            'log' => array(
                'class'=>'CLogRouter',
                'routes'=>array(
                    array(
                        'class'=>'CFileLogRoute',
                        'logFile'=>'cron.log',
                        'levels'=>'error, warning',
                    ),
                    array(
                        'class'=>'CFileLogRoute',
                        'logFile'=>'cron_info.log',
                        'levels'=>'info',
                    ),/*
                    array(
                        'class'=>'CFileLogRoute',
                        'logFile'=>'cron_trace.log',
                        'levels'=>'trace',
                    ),*/
                ),
            ),
            
	),
        'params'=>array(
		// this is used in contact page
            'adminEmail'=>'admin@fiveclicks.ru',
            'host' => 'http://culture48.ru',
	),
);