<?php

class ReceptionModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 0;
    public $backendLayout = '';
    public $moduleCode = 'tables';
    
    public function getVersion()
    {
        return '0.0.1';
    }
    
    public function getParamsLabels()
    {
        return array(
            'adminMenuOrder' => Yii::t('system', 'Порядок следования в меню')
        );
    }
    
    public function getEditableParams()
    {
        return array();
    }
    
    public function getCategory()
    {
        return array('type' => 'info', 'title'=>Yii::t('reception', 'Информационные'));
    }
    
    public function getName()
    {
        return Yii::t('reception', 'Интернет приемная');
    }
    
    public function getDescription()
    {
        return Yii::t('reception', 'Обработка обращений граждан к главе администрации');
    }
    
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
    
    public function getFavoriteActions()
    {
        return array();
    }
    
    public function modulePermission()
    {
        return array(
            'receptionManagement' => array(
                'description' => Yii::t('reception', 'Управление интернет приемной'),
                'operations' => array(
                    'viewReception' => Yii::t('reception', 'Просмотр обращений'),
                    'answerReception' => Yii::t('reception', 'Ответ на обращение'),
                    'deleteReception' => Yii::t('reception', 'Удаление сообщений')
                )
            )
        );
    }
    
    public function init()
    {
        parent::init();
        
        $this->setImport(array(
            'reception.models.*'
        ));
    }
    
    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action))
            return true;
        else
            return false;
    }
}