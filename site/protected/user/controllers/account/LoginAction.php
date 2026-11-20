<?php

class LoginAction extends CAction{
    public function run(){

        $serviceName = Yii::app()->request->getQuery('service');
        if (isset($serviceName)) {
            /** @var $eauth EAuthServiceBase */
            $eauth = Yii::app()->eauth->getIdentity($serviceName);
            $eauth->redirectUrl = Yii::app()->user->returnUrl;
            $eauth->cancelUrl = $this->controller->createAbsoluteUrl('user/account/login');

            try {
                if ($eauth->authenticate()) {
                    //VarDumper::dump($eauth->getIsAuthenticated(), $eauth->getAttributes());
                    $identity = new EAuthUserIdentity($eauth);

                    //successful authentication
                    if ($identity->authenticate()) {
                        Yii::app()->user->login($identity);
                        //VarDumper::dump($identity->id, $identity->name, Yii::app()->user->id); exit;
                        //special redirect with closing popup window
                        $eauth->redirect();
                    } else {
                        //close popup window and redirect to cancelUrl
                        $eauth->cancel();
                    }
                }
                //Something went wrong, redirect to login page
                $this->redirect(array('user/account/login'));
            } catch (EAuthException $e) {
                //save authentication error to session
                Yii::app()->user->setFlash('error', 'EAuthException: '.$e->getMessage());

                //close popup window and redirect to cancelUrl
                $eauth->redirect($eauth->getCancelUrl());
            }
        }




        $form = new LoginForm;
        //$model = new User;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

        if(Yii::app()->request->isPostRequest && !empty($_POST['LoginForm'])){
            $form->setAttributes($_POST['LoginForm']);
            
            if($form->validate()){
                //Yii::app()->user->setFlash(AFlashMessages::NOTICE_MESSAGE, Yii::t('user', 'Вы успешно авторизовались!')); //сообщение об удачном входе
                
                Yii::log(Yii::t('user', 'Пользователь {username} авторизовался!', array('{username}' => $form->login)), CLogger::LEVEL_INFO, UserModule::$logCategory); //запись в логе о входе пользователя
                
                $returnUrl = Yii::app()->user->returnUrl; //url на который возвращаемся после успешного логина
                if(Yii::app()->user->checkAccess('backendAccess')) //если пользователь имеет право доступа к админке...
                    $returnUrl = Yii::app()->getModule('user')->adminReturnUrl; //...возвращаем его в backend
                //elseif(Yii::app()->user->checkAccess('buyerAccess'))
                //	$returnUrl = '/';	// если пользователь имеет права "покупатель" - возвращаем его на главную
                    
                $this->controller->redirect($returnUrl);
            } else{
                Yii::log(Yii::t('user', 'Ошибка авторизации! username => {username}, Password => {password}!', array('{username}' => $form->login, '{password}' => $form->password)), CLogger::LEVEL_ERROR, UserModule::$logCategory); //запись в лог об ошибке при входе
            }
        }
        // display the login form
		$this->controller->render('login',array('model'=>$form));
    }
}