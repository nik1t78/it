<?php

class IndexAction extends CAction{
    
    public function run(){
        $model = Settings::model()->findAll();
        
        if($model === null)
            throw new CHttpException(404, Yii::t('settings', 'В БД не найдено ни одного параметра. Возможно вы неправильно установили систему. Обратитесь к администратору.'));
        
        
        $this->controller->render('index', array('model' => $model));
    }
}