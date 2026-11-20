<?php

class UpdateAction extends CAction{
    
    public function run($id){        
        $criteria = new CDbCriteria;
        $criteria -> select = 'id, name, full_name, email, role, salt';
		$model=$this->controller->loadModel($id, $criteria);
        // Uncomment the following line if AJAX validation is needed
		$this->controller->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
            if($model->validate()){
                if($model->save())
				    $this->controller->redirect(array('view','id'=>$model->id));
            }			
		}

		$this->controller->render('update',array(
			'model'=>$model,
		));
        
    }
}