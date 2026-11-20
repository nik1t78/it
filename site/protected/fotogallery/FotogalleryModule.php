<?php

class FotogalleryModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 0;
    public $backendLayout = '';
    public $moduleCode = 'tables';
    public $linkable = true;
    
    public static function rules (){
        return array(            
            'fotogallery/<iblock:\w+>/<section:[\w_\/-]+>/<e_id:[\d]+>'=>'fotogallery/default/element',          //выводим элемент по id
            'fotogallery/<iblock:\w+>/<section:[\w_\/-]+>/e/<e_code:[\w_-]+>'=>'fotogallery/default/element',    //выводим элемент по коду
            'fotogallery/<iblock:\w+>/<section:[\w_\/-]+>/type_<type:\w+>'=>'fotogallery/default/view',       //выводим индекс раздела с учетом типа
            'fotogallery/<iblock:\w+>/e/<e_code:[\w_-]+>'=>'fotogallery/default/element',
            
            'fotogallery/<gallery:\d+>/<section:\d+>/<foto:\w+>'=>'fotogallery/default/element',
            'fotogallery/<gallery:\d+>/<section:\d+>'=>'fotogallery/default/view',                       //выводим индекс раздела
            'fotogallery/<gallery:\d+>' => 'fotogallery/default/view',                                         //выводим индекс инфоблока - корневые разделы и элементы
            'fotogallery'=>'fotogallery/default/index',
        );
    }
    
    public function getVersion(){
        return '0.0.1';
    }
    
    /**
     * Значения параметров модуля, используемых при настройках модуля
     **/
    public function getParamsLabels(){
        return array(
            'adminMenuOrder' => Yii::t('foto', 'Порядок следования в меню'),
        );
    }
    
    /**
     * Параметры модуля, которые можно поменять через админку 
     **/
    public function getEditableParams(){
        return array(
            //'maxCaptchaLength',
            //'showCaptcha' => $this->getChoice(),
        );
    }
    
    /**
     * Категория модуля, используется для сортировки модулей в админке
     **/
    public function getCategory(){
        return array('type'=>'info', 'title'=>Yii::t('foto', 'Фотогалереи'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('foto', 'Фотогалереи');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('foto', 'Создание фотогалерей');
    }
            
    /** 
     * Возвращает true если модуль должен быть указан в меню админки и false если не должен
     **/
    public function getShowInAdminMenu(){
        return $this->getModuleSettings('showInAdminMenu');
    }
    
    public function getAdminMenuOrder(){
        return $this->getModuleSettings('adminMenuOrder');
    }
    
    public function getBackendLayout(){
        return $this->getModuleSettings('backendLayout');
    }
    
    public function getLinkable(){
        return $this->getModuleSettings('linkable');
    }
    
    /**
     * Избранные действия. Наиболее требуемые действия модуля. Выводятся в боковом меню при активизации модуля
     **/
    public function getFavoriteActions(){
        return array(
            'list' => Yii::t('foto', 'Список'),
            'create' => Yii::t('foto', 'Создать')
        );
    }
    
    public function modulePermission(){
        return array(
            'fotoManagement' => array(
                'description' => Yii::t('foto', 'Управление фотогалереями'),
                'operations' => array(
                    'createFotogal' => Yii::t('foto', 'Создание фотогалереи'),
                    'viewFotogal' => Yii::t('foto', 'Просмотр списка фотогалерей'),
                    'readFotogal' => Yii::t('foto', 'Просмотр фотогалереи'),
                    'updateFotogal' => Yii::t('foto', 'Изменение фотогалереи'),
                    'deleteFotogal' => Yii::t('foto', 'Удаление фотогалереи'),
                    'addFoto' => Yii::t('foto', 'Добавление фотографий'), 
                    'deleteFoto' => Yii::t('foto', 'Удаление фотографий'),                   
                )
            )            
        );
    }
    
	
    
    public function init()
    {
	// this method is called when the module is being created
	// you may place code here to customize the module or the application
        
        parent::init();
        // подключаем модели и компоненты модуля
	$this->setImport(array(
		'fotogallery.models.*',
		'fotogallery.components.*',
		'fotogallery.widgets.*',
	));
    }
    
    public function beforeControllerAction($controller, $action) {
        if(parent::beforeControllerAction($controller, $action))
            return true;
        else
            return false;
    }
          
    public function loadSections()
    {
        $result = array();
        $sections = FotoGallery::model()->findAll();
        foreach ($sections as $section) {
            $result[$section->id] = $section->title;
        }
        return $result;
    }
}
