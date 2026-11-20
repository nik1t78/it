<?php

class ListAction extends CAction{
    
    public function run(){        
        $dataProvider=new CActiveDataProvider('Users', array(
            'pagination'=>array(
                'pageSize'=>5,
            ),
        ));
        $this->controller->render('list',array(
			'dataProvider'=>$dataProvider,
		));
    }
}