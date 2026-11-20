<?php

class UserConfirmAction extends CAction{
    
    public function run($id){        
        if(Yii::app()->request->isPostRequest){
            $user = User::model()->findByPk($id);
            //$model->role = User::ROLE_USER;
            if(User::model()->updateByPk($id, array('role'=>User::ROLE_USER))){
                EmailMessenger::send($user->email, Yii::t('user', 'Ваша учетная запись на '.Yii::app()->request->hostInfo.' активирована.'), Yii::t('user', "Ваша учетная запись на сайте ".Yii::app()->request->hostInfo." успешно активирована \r\nДля того, чтобы авторизоваться перейдите по адресу ".Yii::app()->request->hostInfo.Yii::app()->createUrl(Yii::app()->params->loginUrl).", используйте логин ".$user->login." и пароль, указанный при регистрации.\r\n"));
                echo json_encode(array(
                    'status' => 'success'
                ));
                Yii::app()->end();
            }
            echo json_encode(array(
                'status' => 'error',
                'message' => 'При одобрении записи возникла ошибка.'
            ));
        }else{
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
	}
    }
}