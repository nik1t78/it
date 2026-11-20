<?php

class IndexAction extends CAction{
    
    public function run(){
        $model = new IBlock;
        
        $criteria = new CDbCriteria();
        $criteria->order = 'id ASC';
        $iblocks = $model->findAll($criteria);
        
        $this->controller->render('index', array('iblocks'=>$iblocks));
    }
        
}
