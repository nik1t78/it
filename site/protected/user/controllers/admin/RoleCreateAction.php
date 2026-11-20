<?php

class RoleCreateAction extends CAction{
    
    public function run(){
                
        $model=new RolesForm;

	if(isset($_POST['RolesForm']))
	{
            $model->attributes = $_POST['RolesForm'];
            if($model->validate()){
                $auth = Yii::app()->authManager;
                $role = $auth->createRole($_POST['RolesForm']['name'], $_POST['RolesForm']['description']);
                if(isset($_POST['RolesForm']['rules'])){
                    $subItems = array();
                    foreach($_POST['RolesForm']['rules'] as $rule=>$val){
                        if(array_key_exists($rule, $subItems)) continue;
                        
                        $subItems = $auth->getItemChildren($rule); //получаем дочерние элементы текущего. Если они есть - значит текущий - задача. Пропускаем все дочерние элементы задачи и наследуем ее
                        
                        $role->addChild($rule);
                    }
                }
                $auth->save();
                $this->controller->redirect(array('roles'));
            }
            
            /*
            $model->attributes=$_POST['Role'];
            if($model->validate()){
    		if($model->save())
    			$this->controller->redirect(array('view','id'=>$model->id));
            }*/
	}

        $this->controller->render('rolecreate',array(
            'model'=>$model,	
            'permissions'=>$model->getPermissions()
        ));
    }
}