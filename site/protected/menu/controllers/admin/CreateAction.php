<?php

class CreateAction extends CAction{
    
    public function run(){
		$model=new Menu;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Menu']))
		{
			$model->attributes=$_POST['Menu'];
			if($model->save())
				$this->controller->redirect(array('index'));
		}

		$this->controller->render('create',array(
			'model'=>$model,
		));
        
    }
}