<?php

class MenuModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 1;
    public $backendLayout = '';
    public $moduleCode = 'typo';
    
    public function getVersion(){
        return '0.0.1';
    }
    
    /**
     * Значения параметров модуля, используемых при настройках модуля
     **/
    public function getParamsLabels(){
        return array(
            'adminMenuOrder' => Yii::t('menu', 'Порядок следования в меню'),
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
        return array('type'=>'core', 'title'=>Yii::t('artcms', 'Ядро'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('menu', 'Меню');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('menu', 'Управление меню');
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
    
    /**
     * Избранные действия. Наиболее требуемые действия модуля. Выводятся в боковом меню при активизации модуля
     **/
    public function getFavoriteActions(){
        return array(
            'list' => Yii::t('menu', 'Список'),
            'create' => Yii::t('menu', 'Создать')
        );
    }
    
    
    public function modulePermission(){
        return array(
            'menuManagement' => array(
                'description' => Yii::t('menu', 'Управление меню'),
                'operations' => array(
                    'createMenu' => Yii::t('menu', 'Создание блока меню'),
                    'viewMenu' => Yii::t('menu', 'Просмотр списка существующих меню'),
                    'readMenu' => Yii::t('menu', 'Просмотр меню'),
                    'updateMenu' => Yii::t('menu', 'Изменение меню'),
                    'deleteMenu' => Yii::t('menu', 'Удаление меню'),                    
                )
            )            
        );
    }
    
	
    
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
        
        ///VarDumper::dump(Yii::app()->modules);
        
        parent::init();
        // подключаем модели и компоненты модуля
		$this->setImport(array(
			'menu.models.*',
			'menu.components.*',
		));
	}
}
