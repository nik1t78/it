<?php

class RoleUpdateAction extends CAction{
    
    public function run($name){     
        $permissions = array();
        $model = new RolesForm;
        $auth = Yii::app()->authManager;
        $role = $auth->getItemChildren($name);
        
        
	if(isset($_POST['RolesForm']))
	{
            $model->attributes = $_POST['RolesForm'];
            if($model->validate()){                                
                $currRole = $auth->getAuthItem($name);
                if(isset($_POST['RolesForm']['rules'])){
                    foreach($role as $key=>$val){ //Удаляем права, которые были отменены в форме
                        if(!array_key_exists($key, $_POST['RolesForm']['rules'])){
                            $currRole->removeChild($key);
                        }
                    }
                    $subItems = array();
                    foreach($_POST['RolesForm']['rules'] as $key=>$val){
                        if(!array_key_exists($key, $role)){
                            if(array_key_exists($key, $subItems)) continue;
                            $subItems = $auth->getItemChildren($key); //получаем дочерние элементы текущего. Если они есть - значит текущий - задача. Пропускаем все дочерние элементы задачи и наследуем ее
                        
                            $currRole->addChild($key);
                        }
                    }
                   /* 
                    foreach($_POST['RolesForm']['rules'] as $rule=>$val){
                        if(array_key_exists($rule, $subItems)) continue;
                        
                        $subItems = $auth->getItemChildren($rule); //получаем дочерние элементы текущего. Если они есть - значит текущий - задача. Пропускаем все дочерние элементы задачи и наследуем ее
                        
                        $role->addChild($rule);
                    }*/
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
        
        
        
        
        $rules = $model->getPermissions();
        
        foreach($role as $item=>$value){            
            foreach($rules['items'] as $task=>$operations){
                if($task == $item) {
                    $rules['items'][$task]['active'] = true;
                    continue;
                }
                foreach($operations['items'] as $operation=>$val){
                    if($operation == $item){
                        $rules['items'][$task]['items'][$operation]['active'] = true;
                        continue;
                    }
                }                
            }
        }
        
        $model->name = $name;
        $model->description = $rules['roles'][$name]->description;
        /*
        $criteria = new CDbCriteria;
        $criteria->condition = '';
	
        $model=$this->controller->loadModel($id, $criteria);
        // Uncomment the following line if AJAX validation is needed
		$this->controller->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
                    $model->attributes=$_POST['User'];
                    if($model->validate()){
                        if($model->save())
                            $this->controller->redirect(array('view','id'=>$model->id));
                    }			
		}
*/
		$this->controller->render('roleupdate',array(
                    'model'=>$model,
                    'permissions'=>$rules
		));
        
    }
}