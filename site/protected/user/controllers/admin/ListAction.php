<?php

class ListAction extends CAction{
    
    public function run(){        
        $dataProvider=new CActiveDataProvider('User', array(
            'pagination'=>array(
                'pageSize'=>20,
            ),
        ));
        $this->controller->render('list',array(
			'dataProvider'=>$dataProvider,
		));
    }
    
    
}