<?php

class PollsModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 0;
    public $backendLayout = '';
    public $moduleCode = 'tables';
    
    /*
     * Правила роутинга
     */
    public static function rules()
    {
        return array(
            'polls/<controller:\w+>/<action:\w+>' => 'polls/<controller>/<action>',
            'polls/<controller:\w+>' => 'polls/<controller>',
            'polls' => 'polls/default/index'
        );
    }
    
    public function getVersion()
    {
        return '0.0.1';
    }
    
    
    /**
     * Значения параметров модуля, используемых при настройках модуля
     */
    public function getParamsLabels()
    {
        return array(
            'adminMenuOrder' => Yii::t('polls', 'Порядок следования в меню'),
        );
    }
    
    /**
     * Параметры модуля, которые можно поменять через админку
     */
    public function getEditableParams()
    {
        return array();
    }
    
    /**
     * Категория модуля, используется для сортировки модулей в админке
     */
    public function getCategory()
    {
        return array('type'=>'info', 'title'=>Yii::t('polls', 'Информационные'));
    }
    
    /**
     * Возвращает название модуля
     */
    public function getName()
    {
        return Yii::t('polls', 'Голосования');
    }
    
    /**
     * Возвращает описание модуля
     */
    public function getDescription()
    {
        return Yii::t('polls', 'Опросы и голосования');
    }
    
    /**
     * Возвращает true если модуль должен быть указан в меню админки и false если не должен
     */
    public function getShowInAdminMenu()
    {
        return $this->getModuleSettings('showInAdminMenu');
    }
    
    public function getAdminMenuOrder()
    {
        return $this->getModuleSettings('adminMenuOrder');
    }
    
    public function getBackendLayout()
    {
        return $this->getModuleSettings('backendLayout');
    }
    
    /*
     * Избранное действие. Наиболее требуемые действия модуля. Выводятся в боковом меню при активации модуля
     */
    public function getFavoriteActions()
    {
        return array();
    }
    
    public function modulePermission()
    {
        return array(
            'pollsManagement' => array(
                'description' => Yii::t('polls', 'Управление голосованиями'),  
                'operations' => array(
                    'listPolls' => Yii::t('polls', 'Просмотр списка голосований'),
                    'viewPolls' => Yii::t('polls', 'Просмотр голосования'),
                    'createPolls' => Yii::t('polls', 'Создание голосования'),
                    'updatePolls' => Yii::t('polls', 'Редактирование голосования'),
                    'deletePolls' => Yii::t('polls', 'Удаление голосования'),
                )
            )
        );
    }
    
    public function init()
    {
        parent::init();
        
        $this->setImport(array(
            'polls.models.*',
        ));
    }
    
    public function beforeControllerAction($controller, $action) {
        if(parent::beforeControllerAction($controller, $action))
            return true;
        else 
            return false;
    }
}