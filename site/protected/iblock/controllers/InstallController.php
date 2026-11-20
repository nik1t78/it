<?php

class InstallController extends BackendController{
    public $defaultAction = 'index';
    public $menu = array();
    public $layout = '//layouts/column1';
    
    public function init(){     
        parent::init();
        
    }
    
    public function actionIndex(){
        $this->render('index');
        $this->installPermission();
    }
    
    protected function installPermission(){
        $permission = $this->module->modulePermission();
        $auth = Yii::app()->authManager;
        $existsOperations = $auth->operations;                  //получить все операции
        $existsTasks = $auth->tasks;                            //получить все задачи

        
        foreach($permission as $taskName=>$taskVal){
            if(!array_key_exists($taskName, $existsTasks)) {
                $task = $auth->createTask($taskName, $taskVal['description']);
            } else {
                $task = $existsTasks[$taskName];
            }
            
            foreach($taskVal['operations'] as $operation=>$description){
                if(array_key_exists($operation, $existsOperations)) //если операция уже существует, пропускаем ее импорт
                        continue;

                if(is_array($description)){ //если есть правила, то это уже задача
                    if(array_key_exists($operation, $existsTasks))
                            continue;
                    $auth->createTask($operation, $description['description'], $description['bizRule']); //создаем задачу
                }else{
                    $auth->createOperation($operation, $description);   //создаем операцию
                    $task->addChild($operation);                     //наследуем операцию в задаче
                }
            }
            
        }
        $auth->save();
    }


    
    /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
    public function loadModel($id, $criteria='')
    {
	$model=User::model()->findByPk($id, $criteria);
	if($model===null)
            throw new CHttpException(404,'The requested page does not exist.');
	return $model;
    }
    
    /**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
    public function performAjaxValidation($model)
    {
	if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
	{
            echo CActiveForm::validate($model);
            Yii::app()->end();
	}
    }
}