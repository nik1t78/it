<?php

class TextBlockModule extends AWebModule
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
        return array('type'=>'tblock', 'title'=>Yii::t('tblock', 'Текстовые блоки'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('tblock', 'Текстовые блоки');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('tblock', 'Добавление на страницах блоков с произвольным текстом.');
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
        return array(/*
            'createItem' => array(
                'title' => Yii::t('tblock', 'Добавить прайс-лист'),
                'url' => Yii::app()->createUrl('textblock/admin/create'),
                'icon' => 'plus',
            ),*/
        );
    }
    
    public function modulePermission(){
        return array(
            'textblockManagement' => array(
                'description' => Yii::t('tblock', 'Управление текстовыми блоками'),
                'operations' => array(
                    'createTextblock' => Yii::t('tblock', 'Создание текстового блока'),
                    'viewTextblock' => Yii::t('tblock', 'Просмотр списка текстовых блоков'),
                    'readTextblock' => Yii::t('tblock', 'Просмотр текстового блока'),
                    'updateTextblock' => Yii::t('tblock', 'Изменение текстового блока'),
                    'deleteTextblock' => Yii::t('tblock', 'Удаление текстового блока'),                    
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
			'textblock.models.*',
			'textblock.components.*',
            ));
            
        }
}
