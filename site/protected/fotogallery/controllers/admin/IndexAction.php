<?php

class IndexAction extends CAction{
    
    public function run(){        
        $galleries = FotoGallery::model()->findAll();
        
        $this->controller->render('index', array('galleries'=>$galleries));
    }
        
}
