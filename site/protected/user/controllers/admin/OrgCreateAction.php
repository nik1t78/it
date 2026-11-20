<?php

class OrgCreateAction extends CAction{
    
    public function run(){
                
        $model=new Contragent('create');

	// Uncomment the following line if AJAX validation is needed
	$this->controller->performAjaxValidation($model);

	if(isset($_POST['Contragent']))
	{
            $model->attributes=$_POST['Contragent'];
            if($model->validate()){
    		if($model->save())
    			$this->controller->redirect(array('orgview','id'=>$model->id));
            }
	}

        $this->controller->render('orgcreate',array(
			'model'=>$model,
		));
    }
}