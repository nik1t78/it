<?php

class IndexAction extends CAction{
    
    public function run(){        
        $dataProvider=new CActiveDataProvider('Pages', array(
            'pagination'=>array(
                'pageSize'=>15,
            ),
        ));
        $this->controller->render('list',array(
			'dataProvider'=>$dataProvider,
		));
    }
}