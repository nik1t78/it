<?php

class FaqModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 21;
    public $backendLayout = '';
    public $moduleCode = 'charts';
    
    public function getVersion(){
        return '0.0.1';
    }
    
    /*
     * Правила роутинга
     */
    public static function rules (){
        return array(            
            'faq/<action:\w+>'=>'faq/default/<action>',
            'faq'=>'faq/default/index',
        );
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
        return array('type'=>'price', 'title'=>Yii::t('faq', 'Вопрос-ответ'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('faq', 'Вопрос-ответ');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('faq', 'Модуль ответов на часто задаваемые вопросы');
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
                'title' => Yii::t('price', 'Добавить прайс-лист'),
                'url' => Yii::app()->createUrl('price/admin/create'),
                'icon' => 'plus',
            ),*/
        );
    }
    
    
    public function modulePermission(){
        return array(
            'faqManagement' => array(
                'description' => Yii::t('faq', 'Управление Вопрос-ответ'),
                'operations' => array(
                    'createFaq' => Yii::t('faq', 'Создание вопроса вручную'),
                    'viewFaq' => Yii::t('faq', 'Просмотр списка вопросов'),
                    'updateFaq' => Yii::t('faq', 'Изменение вопроса/ ответ'),
                    'deleteFaq' => Yii::t('faq', 'Удаление вопроса'),                    
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
			'faq.models.*',
			'faq.components.*',
            ));
            
        }
}
