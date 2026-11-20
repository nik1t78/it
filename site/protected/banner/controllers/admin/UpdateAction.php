<?php

class UpdateAction extends CAction{
    
    public function run($id){
        $id = (int) $id;
                
        $model=$this->controller->loadModel($id);
        
        
        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);

	if(isset($_POST['Banner']))
	{            
            $model->attributes = $_POST['Banner'];  
            if($model->validate()){
                
                if($model->save(false)){                    
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('banner', 'Изменения сохранены.'));
                    $this->controller->redirect(array('/banner/admin/index'));
                }
            }		
	}
        
	$this->controller->render('update',array(
            'model'=>$model
	));
        
    }
}