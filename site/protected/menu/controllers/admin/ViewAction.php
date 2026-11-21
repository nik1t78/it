<?php

class ViewAction extends CAction{
    
    public function run($id){    
        $model=Menu::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('menu', 'Меню не найдено'));
        
        $this->controller->render('view', array('model'=>$model));
    }
}