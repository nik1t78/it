<?php

class UpdateAction extends CAction{

    public function run($id){
        $id = (int) $id;

        $model=$this->controller->loadModel($id);


        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);

	if(isset($_POST['Faq']))
	{
            $model->attributes = $_POST['Faq'];
            $model->q_date = DateFormat::convertStrToDb($_POST['Faq']['q_date'], 'd.m.Y');
            if(!empty($_POST['Faq']['a_date']))
                $model->a_date = DateFormat::convertStrToDb($_POST['Faq']['a_date'], 'd.m.Y');

            if($model->validate()){

                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('faq', 'Вопрос успешно обновлен.'));
                    $this->controller->redirect(array('/faq/admin/index'));
                }
            }
	}
        $model->q_date = DateFormat::convertDbToStr($model->q_date, 'd.m.Y', '', 'medium', false);
        if(!empty($model->a_date))
            $model->a_date = DateFormat::convertDbToStr ($model->a_date, 'd.m.Y', '', 'medium', false);
	$this->controller->render('update',array(
            'model'=>$model
	));

    }
}
