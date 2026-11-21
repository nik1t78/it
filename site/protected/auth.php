<?php

return array(
	'buyerAccess'=>array(
		'type'=>CAuthItem::TYPE_OPERATION,
		'description'=>'Права покупателя',
		'bizRule'=>null,
		'data'=>null
	),
    'backendAccess'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Доступ к админке',
        'bizRule'=>null,
        'data'=>null
    ),
    'usersAdminAccess'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Доступ к администрированию пользователей',
        'bizRule'=>null,
        'data'=>null
    ),
    'settingsAdminAccess'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Доступ к настройкам сайта',
        'bizRule'=>null,
        'data'=>null
    ),
    'filemanagerAdminAccess'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Доступ к файл-менеджеру',
        'bizRule'=>null,
        'data'=>null
    ),
    'menuAdminAccess'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Доступ к модулю "Меню" в панели администрирования',
        'bizRule'=>null,
        'data'=>null
    ),
    'pagesAdminAccess'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Доступ к модулю "Структура сайта" в панели администрирования',
        'bizRule'=>null,
        'data'=>null
    ),
    'guest'=>array(
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Guest',
        'bizRule'=>null,
        'data'=>null
    ),
    'banned'=>array(
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Banned',
        'children'=>array(
            'guest', //унаследуемся от гостя
        ),
        'bizRule'=>null,
        'data'=>null
    ),
    'user'=>array(
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'User',
        /** 'children'=>array(
            'guest', //унаследуемся от гостя
        ),**/
        'bizRule'=>null,
        'data'=>null
    ),
    'moderator'=>array(
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Moderator',
        'children'=>array(
            'user', //позволим модератору все, что позволено пользователю
        ),
        'bizRule'=>null,
        'data'=>null
    ),
    'administrator'=>array(
        'type'=>CAuthItem::TYPE_ROLE,
        'description'=>'Administrator',
        'children'=>array(
            'moderator',
            'backendAccess',
            'usersAdminAccess',
            'menuAdminAccess',
            'pagesAdminAccess',
            'settingsAdminAccess',
            'filemanagerAdminAccess',
        ),
        'bizRule'=>null,
        'data'=>null
    ),
);