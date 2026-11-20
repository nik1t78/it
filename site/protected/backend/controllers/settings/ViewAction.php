<?php

class ViewAction extends CAction{
    
    public function run($id){    
        $criteria = new CDbCriteria;
        $criteria->select = 'id, name, full_name, email, role';
        $model=Users::model()->findByPk($id, $criteria);
		if($model===null)
			throw new CHttpException(404, Yii::t('users', 'Пользователи не найдены'));
        
        $this->controller->render('view', array('model'=>$model));
    }
}