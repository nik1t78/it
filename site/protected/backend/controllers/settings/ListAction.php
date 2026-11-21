<?php

class ListAction extends CAction{
    
    public function run(){      
    /**  
        $dataProvider=new CActiveDataProvider('Users', array(/////////////////////////////////// ИЗМЕНИТЬ МОДЕЛЬ!!!!!!!!!!!!!!!!!
            'pagination'=>array(
                'pageSize'=>5,
            ),
        )); **/
        $this->controller->render('list');
    }
}