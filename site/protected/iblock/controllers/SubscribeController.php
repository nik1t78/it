<?php

class SubscribeController extends FrontendController
{
    public $template = 'default';
    public $bodyPageTitle = '';

    public function actions()
    {
        return array(
            'index' => array(
                'class' => 'application.modules.iblock.controllers.subscribe.IndexAction',
            ),
            'ajax' => array(
                'class' => 'application.modules.iblock.controllers.subscribe.AjaxAction',
            ),
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    public function actionSubscribe()
    {
        $model = new IBlockSubscribe();
        $model->send();
    }


    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    public function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'subscribe-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
