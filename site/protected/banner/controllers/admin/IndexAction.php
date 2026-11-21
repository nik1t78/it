<?php

class IndexAction extends CAction{
    
    public function run($id = false){
        $model = new Banner;
        
        $banners = $model->findAll();
        
        $this->controller->render('index', array('model'=>$banners));
    }
        
}