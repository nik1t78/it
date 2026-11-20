<?php

class PagesModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 10;
    public $backendLayout = '';
    public $saveTreeOnAir = true; //Функция "Сохранение на лету". Сохраняет изменения в дереве сайта после каждого действия
    public $ajaxWnd = false; //Некоторые операции выполняются в модальных ajax окнах. Если false - вместо всплывающего окна форма открывается по обычной ссылке
    public $moduleCode = 'typo';
    public $linkable = true;
    
    public function getVersion(){
        return '0.0.1';
    }
    
    /**
     * Правила для роутинга
     * @return type
     */
    public static function rules (){
        return array(   
            '<category:[\w_-]+>' => 'pages/default/view',
            
            /*
            'iblock/<iblock:\w+>/<section:[\w_\/-]+>/<e_id:[\d]+>'=>'iblock/default/element',          //выводим элемент по id
            'iblock/<iblock:\w+>/<section:[\w_\/-]+>/e/<e_code:[\w_-]+>'=>'iblock/default/element',    //выводим элемент по коду
            'iblock/<iblock:\w+>/<section:[\w_\/-]+>/type_<type:\w+>'=>'iblock/default/view',       //выводим индекс раздела с учетом типа
            'iblock/<iblock:\w+>/e/<e_code:[\w_-]+>'=>'iblock/default/element',
            'iblock/<iblock:\w+>/<e_id:[\d]+>'=>'iblock/default/element',
            'iblock/<iblock:\w+>/<section:[\w_\/-]+>'=>'iblock/default/view',                       //выводим индекс раздела
            'iblock/<iblock:\w+>' => 'iblock/default/view',                                         //выводим индекс инфоблока - корневые разделы и элементы
            'iblock'=>'iblock/default/index',*/
        );
    }
    
    /**
     * Значения параметров модуля, используемых при настройках модуля
     
    public function getParamsLabels(){
        return array(
            'adminMenuOrder' => Yii::t('system', 'Порядок следования в меню'),
            'showInAdminMenu' => Yii::t('system', 'Показывать в меню'),
            'backendLayout' => Yii::t('system', 'Шаблон модуля'),
            'saveTreeOnAir' => Yii::t('pages', 'Drag&Drop в дереве структуры'),
            'ajaxWnd' => Yii::t('system', 'Применять всплывающие окна'),
        );
    }**/
    
    /**
     * Параметры модуля, которые можно поменять через админку 
     **/
    public function getEditableParams(){
        return array(
            'adminMenuOrder' => Yii::t('system', 'Порядок следования в меню'),
            'showInAdminMenu' => Yii::t('system', 'Показывать в меню'),
            'backendLayout' => Yii::t('system', 'Шаблон модуля'),
            'saveTreeOnAir' => Yii::t('pages', 'Drag&Drop в дереве структуры'),
            'ajaxWnd' => Yii::t('system', 'Применять всплывающие окна'),
        );
    }
    
    /**
     * Категория модуля, используется для сортировки модулей в админке
     **/
    public function getCategory(){
        return array('type'=>'core', 'title'=>Yii::t('system', 'Ядро'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('pages', 'Структура сайта');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('pages', 'Модуль управления структурой сайта');
    }
            
    /** 
     * Возвращает true если модуль должен быть указан в меню админки и false если не должен
     **/
    public function getShowInAdminMenu(){
        /** $model = Settings::model()->with('params')->find(array('condition' => 't.name=:module AND params.name="showInAdminMenu"', 'params' => array(':module'=>$this->getId())));
        if($model === null){
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'В базе данных не найдены настройки для данного модуля. Возможно модуль установлен не правильно. <br> Будут применены настройки по-умолчанию'));
            return $this->showInAdminMenu;            
        }
        $settings = $model->params; //тут проверка не нужна, т.к. если в SettingsParams не найден параметр - прошлый запрос вызовет ошибку
        return $settings[0]->value;
        **/
        return $this->getModuleSettings('showInAdminMenu');
    }
    
    public function getAdminMenuOrder(){
        return $this->getModuleSettings('adminMenuOrder');
    }
    
    public function getBackendLayout(){
        return $this->getModuleSettings('backendLayout');
    }
    
    /**
     * Избранные действия. Наиболее требуемые действия модуля. Выводятся в боковом меню при активизации модуля
     **/
    public function getFavoriteActions(){
        return array(
            'create' => Yii::t('pages', 'Создать'),
            'list' => Yii::t('pages', 'Структура сайта'),
        );
    }
    
    
    public function modulePermission(){
        return array(
            'pagesManagement' => array(
                'description' => Yii::t('pages', 'Управление страницами сайта'),
                'operations' => array(
                    'createPage' => Yii::t('pages', 'Создание страниц'),
                    'viewPages' => Yii::t('pages', 'Просмотр структуры сайта'),
                    'readPage' => Yii::t('pages', 'Просмотр содержимого страниц'),
                    'updatePage' => Yii::t('pages', 'Редактирование страниц'),
                    'deletePage' => Yii::t('pages', 'Удаление страниц'),                    
                )
            )            
        );
    }
    
	
    
    /**
     * Сохранение изменений структуры сайта на лету.
     **/
    public function getSaveTreeOnAir(){
        return $this->saveTreeOnAir;
    }
    
    /**
     * Использование модальных ajax окон
     **/
    public function getAjaxWnd(){
    	return $this->ajaxWnd;
    }
    
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
        
            parent::init();// подключаем модели и компоненты модуля
            $this->setImport(array(
			'pages.models.*',
			'pages.components.*',
            ));
	}
    
    protected function loadTreeLevel($level)
    {
        $result = array();
        foreach ($level as $node) {
            $result[$node['node']->id]['name'] = $node['node']->title;
            if (!empty($node['children'])) {
                $result[$node['node']->id]['items'] = $this->loadTreeLevel($node['children']);
            }
        }
        return $result;
    }
          
    public function loadSections()
    {
        $result = array();
        $sections = Pages::model()->findByPk(1)->getTreeViewData(true);
        
        //print_r($sections);
        $result = $this->loadTreeLevel($sections);
        return Misc::formatMultiList($result);
    }
}
