<?php

class OrgUpdateAction extends CAction{
    
    public function run($id){        
        
        //$model=$this->controller->loadModel($id);
        $model = Contragent::model()->findByPk($id);
        // Uncomment the following line if AJAX validation is needed
	$this->controller->performAjaxValidation($model);

	if(isset($_POST['Contragent']))
	{
            $model->attributes=$_POST['Contragent'];
            $model->confirm = Contragent::CONFIRM;
            
            if($model->validate()){
                if($model->save())
                    //$this->controller->redirect(array('orglist'));
                    $this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('orglist'));
            }			
	}

	$this->controller->render('orgupdate',array(
            'model'=>$model,
	));
        
    }
}