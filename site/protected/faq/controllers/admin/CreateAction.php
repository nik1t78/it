<?php

class CreateAction extends CAction{
    
    public function run(){
        $model = new Faq;
        
        if(isset($_POST['Faq'])){
            $model->attributes = $_POST['Faq'];
            $model->q_date = DateFormat::convertStrToDb($_POST['Faq']['q_date'], 'd.m.Y');
            if(!empty($_POST['Faq']['a_date']))
                $model->a_date = DateFormat::convertStrToDb($_POST['Faq']['a_date'], 'd.m.Y');
            
            if($model->validate()){
                
                if($model->save()){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('faq', 'Вопрос успешно добавлен'));
                    $this->controller->redirect(array('/faq/admin/index'));
                }
            }
        }
        $this->controller->render('create', array('model'=>$model));
        
    }
}