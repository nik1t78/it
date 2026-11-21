<?php

class UpdateAction extends CAction{
    
    public function run($id){        
        $criteria = new CDbCriteria;
        $criteria -> select = 'id, login, full_name, email, role, salt, address, phoneNum, region';
	$model=$this->controller->loadModel($id, $criteria);
        // Uncomment the following line if AJAX validation is needed
	$this->controller->performAjaxValidation($model);

	if(isset($_POST['User']))
	{
            $model->prevRole = $model->role;
            $model->attributes=$_POST['User'];
            if(isset($_POST['User']['organization_id']))
                $model->organization_id = $_POST['User']['organization_id'];
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
