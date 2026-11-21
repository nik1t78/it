<?php

class DefaultController extends FrontendController
{
    //public $defaultAction = 'index';
    public $menu = array();
    public $mainMenu = array();
    public $sideMenu = array();
    //public $pageTitle = '';
    public $bodyPageTitle = '';
    //public $layout = '//layouts/column2';

    public function actions(){
        return array(
            'index' => array(
        	'class' => 'application.modules.pages.controllers.IndexAction'
            ),
            'ajax' => array(
        	'class' => 'application.modules.pages.controllers.AjaxAction'
            ),
            'view' => array(
                'class' => 'application.modules.pages.controllers.ViewAction'
            ),
            'contact' => array(
                'class' => 'application.modules.pages.controllers.ContactAction'
            ),
            'callback' => array(
                'class' => 'application.modules.pages.controllers.CallbackAction'
            ),
            'captcha'=>array(
                'class'=>'CCaptchaAction',
                'backColor'=>0xFFFFFF,
            ),
        );
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

}
