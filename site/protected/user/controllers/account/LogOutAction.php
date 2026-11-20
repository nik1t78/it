<?php

class LogOutAction extends CAction{
    public function run(){
        Yii::log(Yii::t('user', 'Пользователь {user} вышел!', array('{user}' => Yii::app()->user->getState('username'))), CLogger::LEVEL_INFO, UserModule::$logCategory);
        Yii::app()->user->logout();
        $this->controller->redirect(Yii::app()->homeUrl);
    }
}