<?php

class IndexAction extends CAction{
    
    public function run(){
        $dataProvider=new CActiveDataProvider('Menu', array(
            'pagination'=>array(
                'pageSize'=>5,
            ),
        ));
        $this->controller->render('index',array(
			'dataProvider'=>$dataProvider,
		));
    }
}