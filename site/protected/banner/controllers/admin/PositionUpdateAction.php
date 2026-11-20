<?php

class PositionUpdateAction extends CAction{
    
    public function run($id){
        $id = (int) $id;
                
        $model=  BannerPosition::model()->findByPk($id);
        
        
        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);

	if(isset($_POST['BannerPosition']))
	{            
            $model->attributes = $_POST['BannerPosition'];  
            if($model->validate()){
                
                if($model->save(false)){                    
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('banner', 'Изменения сохранены.'));
                    $this->controller->redirect(array('/banner/admin/position'));
                }
            }		
	}
        
	$this->controller->render('updatepos',array(
            'model'=>$model
	));
        
    }
}