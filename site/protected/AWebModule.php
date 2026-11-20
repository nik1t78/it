<?php

class AWebModule extends CWebModule{
    
    const CHECK_ERROR = 'error';

    const CHOICE_YES  = 1;
    
    const CHOICE_NO   = 0; 
    
    public $linkable = false;
    
    public function init(){
        parent::init();
    }    
    
    public function getChoice()
    {
        return array(
            self::CHOICE_YES => Yii::t('system','да'),
            self::CHOICE_NO  => Yii::t('system','нет')
        );
    }

    public function getAdminPageLink()
    {
        return '/' . strtolower($this->id) . '/admin/';
    }
    
    public function getModuleSettings($paramName){
        $criteria = new CDbCriteria;
        $criteria->condition = 't.name=:module AND params.name=:paramName';
        $criteria->params = array(
            ':module'=>$this->getId(), 
            ':paramName'=>$paramName
        );
        $model = Settings::model()->with('params')->find($criteria);
        
        if($model === null){
    //        Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'В базе данных не найдены настройки для модуля "'.$this->getName().'". Возможно модуль установлен не правильно. <br> Будут применены настройки по-умолчанию'));
            return $this->$paramName;            
        }
        $settings = $model->params; //тут проверка не нужна, т.к. если в SettingsParams не найден параметр - прошлый запрос вызовет ошибку
        return $settings[0]->value;
    }
}