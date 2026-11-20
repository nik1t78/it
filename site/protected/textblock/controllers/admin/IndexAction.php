<?php

class IndexAction extends CAction{
    
    public function run($id = false){
        $model = new Textblock;
        
        $tblocks = $model->findAll();
        
        $this->controller->render('index', array('model'=>$tblocks));
    }
        
}