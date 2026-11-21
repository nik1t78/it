<?php

class SettingsModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 0;
    public $backendLayout = '';
    public $moduleCode = 'errors';
    
    public function getVersion(){
        return '0.0.1';
    }
    
    /**
     * Значения параметров модуля, используемых при настройках модуля
     **/
    public function getParamsLabels(){
        return array(
            'adminMenuOrder' => Yii::t('settings', 'Порядок следования в меню'),
        );
    }
    
    /**
     * Параметры модуля, которые можно поменять через админку 
     **/
    public function getEditableParams(){
        return array(
            //'maxCaptchaLength',
            'showCaptcha' => $this->getChoice(),
        );
    }
    
    /**
     * Категория модуля, используется для сортировки модулей в админке
     **/
    public function getCategory(){
        return array('type'=>'core', 'title'=>Yii::t('settings', 'Модуль настроек сайта'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('settings', 'Настройки');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('settings', 'Основные настройки ядра сайта.');
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
            'list' => Yii::t('settings', 'Список'),
            'create' => Yii::t('settings', 'Создать')
        );
    }
    
    
    public function modulePermission(){
        return array(
            'settingsManagement' => array(
                'description' => Yii::t('settings', 'Доступ к настройкам'),
                'operations' => array(
                    'updateSettings' => Yii::t('settings', 'Изменение настроек'),                   
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
			'settings.models.*',
			'settings.components.*',
		));
	}
}
