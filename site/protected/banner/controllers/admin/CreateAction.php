<?php

class CreateAction extends CAction{
    
    public function run(){
        $model = new Banner;
        
        if(isset($_POST['Banner'])){
            $model->attributes = $_POST['Banner'];      
            
            if($model->validate()){        
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('banner', 'Баннер успешно добавлен'));
                    $this->controller->redirect(array('/banner/admin/index'));
                }
            }
        }
        $this->controller->render('create', array('model'=>$model));
        
    }
}