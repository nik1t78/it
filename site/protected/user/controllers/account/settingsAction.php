<?php

class settingsAction extends CAction{
    public function run(){
        $this->controller->sideMenu = array(
            array('label'=>'Настройки', 'url'=>array('/user/account/settings')),
            //array('label'=>'Уведомления', 'url'=>array('/user/account/notifications')),
            array('label'=>'Активные торги', 'url'=>array('/user/account/mybids')),
            array('label'=>'Корзина', 'url'=>array('/user/account/basket')),
            //array('label'=>'История', 'url'=>array('/user/account/history')),            
        );
        
        $criteria = new CDbCriteria;
        $criteria -> select = 'id, login, full_name, email, address, phoneNum, region';
	$model= User::model()->findByPk(Yii::app()->user->id, $criteria);
        $model->scenario = 'settings';
        
        // Uncomment the following line if AJAX validation is needed
	$this->controller->performAjaxValidation($model);

	if(isset($_POST['User']))
	{
            $model->attributes=$_POST['User'];
            if($model->validate()){
                //VarDumper::dump($_POST['User']);
                //VarDumper::dump($model->attributes);
                
                
                if($model->save())
                    $this->controller->redirect(array('office'));
                 
            }			
	}
        
    	$this->controller->render('settings', array('model'=>$model));  
    }
    
}
