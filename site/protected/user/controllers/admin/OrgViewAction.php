<?php

class OrgViewAction extends CAction{
    
    public function run($id){    
        $model=  Contragent::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('user', 'Организации не найдены'));
        
        $this->controller->render('orgview', array('model'=>$model));
    }
}