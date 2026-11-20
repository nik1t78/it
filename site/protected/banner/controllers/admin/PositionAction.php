<?php

class PositionAction extends CAction{
    
    public function run($id = false){
        $model = new BannerPosition();
        
        $banners = $model->findAll();
        
        $this->controller->render('position', array('model'=>$banners));
    }
        
}