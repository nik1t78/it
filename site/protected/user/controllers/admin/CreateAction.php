<?php

class CreateAction extends CAction{
    
    public function run(){
                
        $model=new User('create');

	// Uncomment the following line if AJAX validation is needed
	$this->controller->performAjaxValidation($model);

	if(isset($_POST['User']))
	{
            $model->attributes=$_POST['User'];
            if(isset($_POST['User']['organization_id']))
                $model->organization_id = $_POST['User']['organization_id'];
            if($model->validate()){
    		if($model->save())
    			$this->controller->redirect(array('view','id'=>$model->id));
            }
	}

        $this->controller->render('create',array(
			'model'=>$model,
		));
    }
}
