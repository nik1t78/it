<?php

class CreateAction extends CAction{
    
    public function run(){
        $model = new Textblock;
        
        if(isset($_POST['Textblock'])){
            $model->attributes = $_POST['Textblock'];      
            
            if($model->validate()){        
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('tblock', 'Текстовый блок успешно добавлен'));
                    $this->controller->redirect(array('/textblock/admin/index'));
                }
            }
        }
        $this->controller->render('create', array('model'=>$model));
        
    }
}