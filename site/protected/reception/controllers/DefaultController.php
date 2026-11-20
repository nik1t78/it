<?php

class DefaultController extends FrontendController
{
    public $template = 'default';
    public $bodyPageTitle = '';

    public function actionIndex()
    {
        $model = new ReceptionForm;
        $result = false;
	// Uncomment the following line if AJAX validation is needed
	//$this->performAjaxValidation($model);

        if (isset($_POST['ReceptionForm'])) {
            $model->attributes = $_POST['ReceptionForm'];

            if ($model->validate()){
                $attach = '';
                if (file_exists(Yii::getPathOfAlias('webroot').$model->file) && !is_dir(Yii::getPathOfAlias('webroot').$model->file))
                    $attach = Yii::getPathOfAlias('webroot').$model->file;


                $receptionModel = new Reception;
                foreach ($model->attributes as $key=>$field) {
                    if ($key == 'verifyCode' || $key == 'file') continue;
                    $receptionModel->$key = $model->$key;
                }

                if ($receptionModel->save()) {
                    $emailer = new EmailMessenger();
                    $emailer->from = Yii::app()->params['adminEmail'];
                    $result = $emailer->send(
                        Yii::app()->params['receptionEmail'],
                        'Интернет-обращение с сайта Управления',
                        $this->renderPartial('emailReception', array('model' => $model), true),
                        false,
                        $attach
                    );

                    $this->redirect(Yii::app()->createUrl('reception/default/success'));
                }
            }
        }

        $this->render('index', array('model'=>$model, 'result'=>$result));
    }

    public function actionSuccess()
    {
        $this->render('success');
    }

    public function actions()
    {
        return array(
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
        );
    }

    public function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'reception-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
