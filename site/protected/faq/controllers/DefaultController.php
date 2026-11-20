<?php

class DefaultController extends FrontendController
{
    public $bodyPageTitle = 'Вопрос-ответ';

	public function actionIndex()
	{
        $criteria = new CDbCriteria;
        $criteria->order = 'q_date DESC';
        $faq = Faq::model()->approved()->findAll($criteria);

		$this->render('index', array('questions'=>$faq));
	}

    public function actionSendQuestion()
    {
        $model = new FaqForm;
        $this->performAjaxValidation($model);

        if (isset($_POST['FaqForm'])){
            $model->attributes = $_POST['FaqForm'];
            $model->q_date = DateFormat::convertStrToDb($_POST['FaqForm']['q_date'], 'd.m.Y');
            $valid = $model->validate();

            if ($valid) {
                $faqModel = new Faq;
                foreach ($model->attributes as $key=>$field){
                    if ($key == 'verifyCode') continue;
                    $faqModel->$key = $model->$key;
                }
                $faqModel->save(false);
                echo CJSON::encode(array(
                    'status' => 'success'
                ));
                Yii::app()->end();
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
        }
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


    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    public function performAjaxValidation($model)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='faq-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
