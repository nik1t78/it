<?php

Yii::import('fotogallery.models.*');
DUrlRulesHelper::import('fotogallery');

class FotoSliderWidget extends CWidget
{
    public $gallery = 1;
    public $htmlOptions = array();
    
    
    public function init()
    {
        
    }
    
    public function run()
    {
        $this->render('fotoSlider', array(
            'fotos'=>$this->getFotos()
        ));
    }
    
    private function getFotos()
    {
        $fotos = Foto::model()->findAllByAttributes(array('gallery_id'=>$this->gallery));
        
        return $fotos;
    }
}
