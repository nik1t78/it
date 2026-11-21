<?php

class PositionCreateAction extends CAction{
    
    public function run(){
        $model = new BannerPosition();
        
        if(isset($_POST['BannerPosition'])){
            $model->attributes = $_POST['BannerPosition'];      
            
            if($model->validate()){        
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('banner', 'Баннер успешно добавлен'));
                    $this->controller->redirect(array('/banner/admin/position'));
                }
            }
        }
        $this->controller->render('createpos', array('model'=>$model));
        
    }
}