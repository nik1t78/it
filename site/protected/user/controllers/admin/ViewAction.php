<?php

class ViewAction extends CAction{
    
    public function run($id){    
        $criteria = new CDbCriteria;
        $criteria->select = '*';
        $model=User::model()->findByPk($id, $criteria);
		if($model===null)
			throw new CHttpException(404, Yii::t('user', 'Пользователи не найдены'));
        
        $this->controller->render('view', array('model'=>$model));
    }
}