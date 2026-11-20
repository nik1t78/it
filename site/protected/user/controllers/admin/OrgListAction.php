<?php

class OrgListAction extends CAction{
    
    public function run(){        
        $dataProvider=new CActiveDataProvider('Contragent', array(
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));
        
        $this->controller->render('orglist',array(
            'dataProvider'=>$dataProvider,
	));
    }
    
    
}