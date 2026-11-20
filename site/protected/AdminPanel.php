<?php

class AdminPanel extends CWidget
{
    public $visible = false;
    
    public function init() 
    {
        if ($this->visible) {
            $as = CHtml::asset(dirname(__FILE__).'/assets/');
            $cs=Yii::app()->clientScript;
            $cs->registerCssFile($as.'/adminPanel.css');
            //$cs->registerScriptFile($as.'/js/elfinder.min.js');
        }
    }
    
    public function run()
    {
        if ($this->visible) {
            echo '<div class="adminPanel"><div class="wrap">Пользователь: '.Yii::app()->user->getState('full_name').' ('.User::getRole(Yii::app()->user->id).') <a href="'.Yii::app()->createUrl('user/account/logout').'" class="exit"><span>Выход</span></a> <a class="button" target="_blank" href="'.Yii::app()->createUrl('backend/default/index').'"><span>Панель администрирования</span></a></div></div>';
        }
    }
}
?>
