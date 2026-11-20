<?php

class BannerModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 21;
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
            'adminMenuOrder' => Yii::t('artcms', 'Порядок следования в меню'),
        );
    }
    
    /**
     * Параметры модуля, которые можно поменять через админку 
     **/
    public function getEditableParams(){
        return array(
            'adminMenuOrder' => Yii::t('system', 'Порядок следования в меню'),
            'showInAdminMenu' => Yii::t('system', 'Показывать в меню'),
            'backendLayout' => Yii::t('system', 'Шаблон модуля'),
        );
    }
    
    /**
     * Категория модуля, используется для сортировки модулей в админке
     **/
    public function getCategory(){
        return array('type'=>'banner', 'title'=>Yii::t('banner', 'Баннеры'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('banner', 'Баннеры');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('banner', 'Добавление на страницах баннеров.');
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
            'position' => array(
                'title' => Yii::t('banner', 'Расположения баннеров'),
                'url' => Yii::app()->createUrl('banner/admin/position'),
                'icon' => 'plus',
            ),
            //'position' => Yii::t('banner', 'Расположения баннеров')
        );
    }
    
    public function modulePermission(){
        return array(
            'bannerManagement' => array(
                'description' => Yii::t('banner', 'Управление баннерами'),
                'operations' => array(
                    'createBanner' => Yii::t('banner', 'Создание баннера'),
                    'viewBanner' => Yii::t('banner', 'Просмотр списка баннеров'),
                    'readBanner' => Yii::t('banner', 'Просмотр баннера'),
                    'updateBanner' => Yii::t('banner', 'Изменение баннера'),
                    'deleteBanner' => Yii::t('banner', 'Удаление баннера'),                    
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
			'banner.models.*',
			'banner.components.*',
            ));
            
        }
}
