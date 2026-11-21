<?php

class UpdateAction extends CAction{
    
    public function run($id){        
	$model=$this->controller->loadModel($id);
        
        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);

	if(isset($_POST['Menu']))
	{
            $model->attributes=$_POST['Menu'];
            
            if($model->validate()){
                if($model->save(false)){
                    $this->controller->redirect(array('view','id'=>$model->id));
		}
            }
	}

	$this->controller->render('update',array(
            'model'=>$model,
            'items'=>$model->menuItems(array('order'=>'sort ASC'))
	));
        
    }
}