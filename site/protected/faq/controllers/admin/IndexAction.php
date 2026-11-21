<?php

class IndexAction extends CAction{
    
    public function run($id = false){
        $model = new Faq;
        
        $faq = $model->findAll();
        
        $this->controller->render('index', array('model'=>$faq));
    }
        
}