<?php

/**
 * Получаем массив с установленными модулями
 **/
    $modulesList = scandir(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'modules'.DIRECTORY_SEPARATOR);
    $modules = array();
    foreach($modulesList as $key=>$val){
        if($val == '.' || $val == '..' || $val == '.DS_Store')
            continue;
        $modules[$val] = array('class'=>'application.modules.'.$val.'.'.ucfirst($val).'Module');
    }

    define('MODULES_MATCHES', implode('|', array_keys($modules)));

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('upload', dirname($_SERVER['SCRIPT_FILENAME']).DIRECTORY_SEPARATOR.'upload');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
        'name'=>'Управление социальной политики Липецкой области',//'Управление социальной защиты населения Липецкой области',
        'language'=>'ru',
        'theme'=>'front',
        'defaultController' => 'pages',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
            'application.components.*',
            'application.helpers.*',
            'application.extensions.VarDumper.*',
            'application.extensions.tree.*',
            'application.widgets.*',

            'application.modules.pages.models.*',
            'application.modules.menu.models.*',
            'application.modules.settings.models.*',

            'application.extensions.eoauth.*',
            'application.extensions.eoauth.lib.*',
            'application.extensions.lightopenid.*',
            'application.extensions.eauth.*',
            'application.extensions.eauth.services.*',
	),

        'modules' => array_replace($modules, array(
            //Если какой-то модуль нужнается в переопределении, прописать его здесь
            /*
             * 'user' => array(
             *      'class' => 'application.modules.user.UserModule',
             *      'documentRoot' => $_SERVER['DOCUMENT_ROOT'],
             * ),
             */
            'gii' =>array(
		'class'=>'system.gii.GiiModule',
		'password'=>'111',
		 // If removed, Gii defaults to localhost only. Edit carefully to taste.
		'ipFilters'=>array('127.0.0.1','10.248.23.45', '::1'),
            ),
        )),
        'behaviors' => array(
            array(
                'class' => 'DModuleUrlRulesBehavior',
            ),
        ),
	// application components
	'components'=>array(
            'clientScript' => array(
                'scriptMap' => array(
                    'jquery.js' => '/js/jquery.min.js',
                    'jquery-ui.js' => '/js/jquery-ui.min.js',
                ),
            ),
            'loid' => array(
                'class' => 'application.extensions.lightopenid.loid'
            ),
            'eauth' => array(
                'class' => 'application.extensions.eauth.EAuth',
                'popup' => true, //Use the popup window instead of redirecting
                'cache' => false, //Cache component name or false to disable cache. Defaults to 'cache'.
                'cacheExpire' => 0, //Cache lifetime. Defaults to 0 - means unLimited
                'services' => array( //You can change the providers and their classes
                    /*'google' => array(
                        'class' => 'GoogleOpenIDService',
                        //'reaLm' => '*.example.org',
                    ),
                    'yandex' => array(
                        'class' => 'YandexOpenIDService',
                        //'reaLm' => '*.example.org',
                    ),
                    'steam' => array(
                        'class' => 'SteamOpenIDService',
                        //'reaLm' => '*.example.org',
                    ),
                    'yahoo' => array(
                        'class' => 'YahooOpenIDService',
                        //'reaLm' => '*.example.org',
                    ),
                    'twitter' => array(
                        // register your app here: https://dev.twitter.com/apps/new
                        'class' => 'TwitterOAuthService',
                        'key' => '...',
                        'secret' => '...'
                    ), */
                    'google_oauth' => array(
                        // register your app here: https://code.google.com/apis/console/
                        'class' => 'GoogleOAuthService',
                        'client_id' => '112485008460-2shs0ho5gr3s1qgdgdgc4ib7rfcvbaek.apps.googleusercontent.com',
                        'client_secret' => 'Ihq6JZEZgkIvbkuphkY_waXF',
                        'title' => 'Google (OAuth)',
                    ),
                    'yandex_oauth' => array(
                        // register your app here: https://oauth.yandex.ru/client/my
                        'class' => 'YandexOAuthService',
                        'client_id' => '3ecb36e55df847dd981d03c853a01047',
                        'client_secret' => 'd18d83a8001b4eb4b6fbf1632db13383',
                        'title' => 'Yandex (OAuth)',
                    ),
                    'facebook' => array(
                        // register your app here: https://developers.facebook.com/apps/
                        'class' => 'FacebookOAuthService',
                        'client_id' => '222170151169133',
                        'client_secret' => 'e052e3a170e58289c08d953d139a2b9f',
                    ),
                    'vkontakte' => array(
                        // register your app here: https://vk.com/editapp?act=create&site=1
                        'class' => 'VKontakteOAuthService',
                        'client_id' => '4335661',
                        'client_secret' => '1S3PP0f73PF1M5rkzFZM',
                    ),
                    'mailru' => array(
                        // register your app here: http://api.mail.ru/sites/my/add
                        'class' => 'MailruOAuthService',
                        'client_id' => '720325',
                        'client_secret' => '3f828ad7c32e3337ba3b4cd488837689',
                    ),
                    'odnoklassniki' => array(
                        // register your app here: http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=13992188
                        // ... or here: http://www.odnoklassniki.ru/dk?st.cmd=appsInfoMyDevList&st._aid=Apps_Info_MyDev
                        'class' => 'OdnoklassnikiOAuthService',
                        'client_id' => '1109678592',
                        'client_public' => 'CBALFFCDEBABABABA',
                        'client_secret' => '54789BD6099D243622A4ECB2',
                        'title' => 'Odnokl.',
                    ),/*
                    'linkedin' => array(
                        // register your app here: https://www.linkedin.com/secure/developer
                        'class' => 'LinkedinOAuthService',
                        'key' => '...',
                        'secret' => '...',
                    ),
                    'moikrug' => array(
                        // register your app here: https://oauth.yandex.ru/client/my
                        'class' => 'MoikrugOAuthService',
                        'client_id' => '...',
                        'client_secret' => '...',
                    ),
                    'github' => array(
                        // register your app here: https://github.com/settings/applications
                        'class' => 'GitHubOAuthService',
                        'client_id' => '...',
                        'client_secret' => '...',
                    ),
                    'live' => array(
                        // register your app here: https://manage.dev.live.com/Applications/Index
                        'class' => 'LiveOAuthService',
                        'client_id' => '...',
                        'client_secret' => '...',
                    ),
                    'dropbox' => array(
                        // register your app here: https://www.dropbox.com/developers/apps/create
                        'class' => 'DropboxOAuthService',
                            'client_id' => '...',
                            'client_secret' => '...',
                    ),*/
                )
            ),
            'ePdf' => array(
                'class' => 'application.extensions.yii-pdf.EYiiPdf',
                'params' => array(
                    'mpdf' => array(
                        'librarySourcePath' => 'application.components.MPDF56.*',
                        'constants' => array(
                            '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                        ),
                        'class' => 'mpdf',
                        /*'defaultParams'     => array( // More info: http://mpdf1.com/manual/index.php?tid=184
                                'mode'              => '', //  This parameter specifies the mode of the new document.
                                'format'            => 'A4', // format A4, A5, ...
                                'default_font_size' => 0, // Sets the default document font size in points (pt)
                                'default_font'      => '', // Sets the default font-family for the new document.
                                'mgl'               => 15, // margin_left. Sets the page margins for the new document.
                                'mgr'               => 15, // margin_right
                                'mgt'               => 16, // margin_top
                                'mgb'               => 16, // margin_bottom
                                'mgh'               => 9, // margin_header
                                'mgf'               => 9, // margin_footer
                                'orientation'       => 'P', // landscape or portrait orientation
                            )*/
                    ),
                    'HTML2PDF' => array(
                        'librarySourcePath' => 'application.components.html2pdf.*',
                        'classFile' => 'html2pdf.class.php',
                        /*'defaultParams'     => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil
                                'orientation' => 'P', // landscape or portrait orientation
                                'format'      => 'A4', // format A4, A5, ...
                                'language'    => 'ru', // language: fr, en, it ...
                                'unicode'     => true, // TRUE means clustering the input text IS unicode (default = true)
                                'encoding'    => 'UTF-8', // charset encoding; Default is UTF-8
                                'marges'      => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)
                            )*/
                    )
                )
            ),
            'image' => array(
                    'class'=>'application.extensions.image-component.ImageComponent',
                    'driver'=>'Gd',
                ),
            'cache' => array(
                'class' => 'system.caching.CDummyCache' // кэш пустышка! заменить в продакшене!
            ),
            'request'=>array(
		'class' => 'application.components.EHttpRequest',
		'enableCsrfValidation'=>true,
		'enableCookieValidation'=>false,
            ),
            'user'=>array(
                // enable cookie-based authentication
                'allowAutoLogin'=>true,
                'class' => 'application.modules.user.components.AWebUser',
                'loginUrl' => '/user/account/login', // это параметр для frontend, должен вызываться модуль отличный от User, который используется для авторизации в админке
                //'guestName' => 'Гость',
            ),
            'authManager'=>array(
                'class' => 'CDbAuthManager',
                'connectionID'=>'db',
                //Роль по умолчанию. Все, кто не админы, модераторы и юзеры - гости.
                'defaultRoles'=>array('guest'),
                'showErrors' => YII_DEBUG,
            ),
            // uncomment the following to enable URLs in path-format

            'urlManager'=>array(
                'class' => 'UrlManager',
                'urlFormat'=>'path',
                'rules'=>array(
                    '' => 'pages',
                    //стандартное правило для обработки '/login' как 'site/login' и т.д.
                    //'<action: (login|logout|about)>' => 'site/<action>',

                    'backend'=>'backend/default/index',
                    'backend/<module:\w+>' => '<module>/admin/',
                    'backend/<module:\w+>/<action:\w+>' => '<module>/admin/<action>',
                    'backend/settings/view/<module:\w+>' => 'settings/admin/view',
                    'backend/<action:\w+>'=>'backend/default/<action>',

                    '<module:'.MODULES_MATCHES.'>' => '<module>',

                    //свое правило для URL вида '/Раздел/страница'
                    array(
                        'class' => 'application.components.PageUrlRule',
                        'connectionID' => 'db',
                    ),

                    'index' => '/',
                ),
                'showScriptName' => false,
                'urlSuffix' => '/',
            ),

            'db' => require(dirname(__FILE__) . '/db.php'),
           /* 'db2' => array(
                'class' => 'system.db.CDbConnection',
                'connectionString' => 'mysql:host=localhost;dbname=szn',
                'emulatePrepare' => true,
                'tablePrefix' => '',
                'username' => 'cultu_new',
                'password' => 'whe0shaf',
                'charset' => 'utf8',
                'enableProfiling' => true, // включаем профайлер
                'enableParamLogging' => true, // показываем значения параметров
            ),*/

            'errorHandler'=>array(
                // use 'site/error' action to display errors
                'errorAction'=>'pages/default/error',
            ),
            'log'=>array(
            	'class'=>'CLogRouter',
		'routes'=>array(
                    array(
                        'class' => 'ext.phpconsole.PhpConsoleLogRoute',
                    ),
                    array(
                        'class'=>'CFileLogRoute',
			'levels'=>'error, warning, info',
                    ),
                    // направляем результаты профайлинга в ProfileLogRoute (отображается внизу страницы)
                    /**array(
                        'class'=>'CProfileLogRoute',
                        'levels'=>'profile',
                        'enabled'=> true,
                    ),**/
                                    // uncomment the following to show log messages on web pages
                                    /**
                    array(
                        'class'=>'application.extensions.db_profiler.DbProfileLogRoute',
                        'countLimit' => 1, // How many times the same query should be executed to be considered inefficient
                        'slowQueryMin' => 0.01, // Minimum time for the query to be slow
                    ),
                                    array(
                                            'class'=>'CWebLogRoute',
                        //'categories'=>'application.*',
                        'levels'=>'error, warning, info',
                        'showInFireBug'=> true,
                                    ),**/

                ),
            ),
	),


	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
        'adminEmail'=>'sozan@admlr.lipetsk.ru',
        'receptionEmail'=>'sozan@admlr.lipetsk.ru',
	  	//'receptionEmail'=>'test-page-studio@ya.ru',
        'appointmentsEmail'=>'sozan@admlr.lipetsk.ru',
            'loginUrl'=>'/user/account/login',
            'cookie_lifetime' => 3600*24*7, //время жизни cookie при авторизации
            'email' => array(
                //'sender' => 'Mail_Mime',
            )
	),
);
