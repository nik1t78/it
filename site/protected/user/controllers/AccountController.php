<?php

class AccountController extends FrontendController{
    //public $layout = '//layouts/column2';
    public $bodyPageTitle = '';
    /**
     * Подключения действий контроллера
     */
    public function actions(){
        return array(
            'ajax' => array(
                'class' => 'application.modules.user.controllers.account.AjaxAction'
            ),
            'login' => array(
                'class' => 'application.modules.user.controllers.account.LoginAction'
            ),
            'logout' => array(
                'class' => 'application.modules.user.controllers.account.LogOutAction'
            ),
            'office' => array(
                'class' => 'application.modules.user.controllers.account.officeAction'
            ),
            'basket' => array(
                'class' => 'application.modules.user.controllers.account.basketAction'
            ),
            'sendOrder' => array(
                'class' => 'application.modules.user.controllers.account.sendOrderAction'
            ),
            'settings' => array(
                'class' => 'application.modules.user.controllers.account.settingsAction'
            ),
            'notifications' => array(
                'class' => 'application.modules.user.controllers.account.notificationsAction'
            ),
            'history' => array(
                'class' => 'application.modules.user.controllers.account.historyAction'
            ),
            'mybids' => array(
                'class' => 'application.modules.user.controllers.account.myBidsAction'
            ),
            'registration' => array(
                'class' => 'application.modules.user.controllers.account.registrationAction'
            ),
        );
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    public function performAjaxValidation($model)
    {
	if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
	{
            echo CActiveForm::validate($model);
            Yii::app()->end();
	}
    }

    /**
     * * Returns the data model based on the primary key given in the GET variable.
     * * If the data model is not found, an HTTP exception will be raised.
     * * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $criteria='')
    {
	$model=User::model()->findByPk($id, $criteria);
	if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
	return $model;
    }
}
