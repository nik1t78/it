<?php

class registrationAction extends CAction{
    public function run(){
                
        $model = new User('registration');
        $contragent = new Contragent('registration');
        
	// Uncomment the following line if AJAX validation is needed
	$this->controller->performAjaxValidation($model);
        
        if(Yii::app()->request->isPostRequest && !empty($_POST['User'])){            
                $model = new User('registration');
                $newData = $_POST['User'];
                unset($newData['organization']);
                $regType = 'user';
            
            
            $model->attributes = $newData;
            if(isset($_POST['User']['organization_id']))
                $model->organization_id = $_POST['User']['organization_id'];
            
            if($model->validate()){
                /** Обычное сохранение **/
                
                //echo $this->controller->renderPartial('emailUserRegistration', array('user'=>$model), true);
                $tmp_pass = $model->password;
                if($model->save()){
                    //Отправляем на почту пользователя и админа сообщения о регистрации
                    $emailSender = new EmailMessenger;
                    $emailSender->send($model->email, Yii::t('user', 'Регистрация на '.Yii::app()->request->hostInfo), $this->controller->renderPartial('emailUserRegistration', array('user'=>$model, 'pass'=>$tmp_pass), true));
                    $this->controller->render('registrationResult', array('result'=>true, 'regType'=>$regType));
                    Yii::app()->end();
                }
                $this->controller->render('registrationResult', array('result'=>false));
                Yii::app()->end();
                               
                /**
                Yii::log(Yii::t('user', 'Пользователь {username} авторизовался!', array('{username}' => $form->name)), CLogger::LEVEL_INFO, UserModule::$logCategory); //запись в логе о входе пользователя
                **/
            } /*else{
                $this->controller->render('registrationResult', array('result'=>false));
                Yii::app()->end();
                //Yii::log(Yii::t('user', 'Ошибка регистрации! username => {username}, Password => {password}!', array('{username}' => $form->name, '{password}' => $form->password)), CLogger::LEVEL_ERROR, UserModule::$logCategory); //запись в лог об ошибке при входе
            }*/
        }
        
    	$this->controller->render('registration', array('model'=>$model, 'contragent'=>$contragent));  
    }
}
