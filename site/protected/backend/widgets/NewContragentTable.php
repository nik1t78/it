<?php

class NewContragentTable extends CWidget{
    public $result = array();
    
    public function run(){    
        $criteria = new CDbCriteria;
        //$criteria->condition = 'confirm=:confirm';
        //$criteria->params = array(':confirm'=>  Contragent::NON_CONFIRM);
        $criteria->order = 'id DESC';
        
        $contragents = Contragent::model()->findAll($criteria);
        
        foreach ($contragents as $firm){
                      
            $this->result[] = array(
                'id' => $firm->id,
                'name' => $firm->name,
                'region' => $firm->region,
                'tin' => $firm->tin,
                'confirm' => $firm->confirm
            );        
        }
        $_POST['returnUrl'] = '/backend/';
        $this->render('newContragentsTable');        
    }
    
}
