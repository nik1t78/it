<?php

class DefaultController extends BackendController
{    
    public $layout = '//layouts/backendIndex';
        
    public function actionIndex()
    {   
        /*
                $availableModules = '';
                $modulesTemp = Yii::app()->getModule('backend')->getModules(false);
                //VarDumper::dump($modulesTemp);
                foreach($modulesTemp as $key=>$val){
                    //$availableModules .= $key.' '.$val.'<br>';
                    if($key === 'modules'){
                        foreach($val as $moduleName=>$moduleArr){
                            $availableModules .= '<li>'.Yii::app()->getModule($moduleName)->getName().'</li>';
                        }
                    }

                } */
        //Yii::app()->user->setFlash(AFlashMessages::NOTICE_MESSAGE, Yii::t('flash', 'Тестовое сообщение. ')); //сообщение об удачном входе
        //Yii::app()->user->setFlash(AFlashMessages::WARNING_MESSAGE, Yii::t('flash', 'Тестовое предупреждение'));
        //Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('flash', 'Тестовая ошибка'));
                                
        $this->render('index');                  
    }
        
        
    public function actionInstall(){
        $this->render('index');
        $this->installPermission();
    }
    
    protected function installPermission(){
        $permission = $this->module->modulePermission();
        $auth = Yii::app()->authManager;
        $existsOperations = $auth->operations;                  //получить все операции
        $existsTasks = $auth->tasks;                            //получить все задачи
        
        foreach($permission as $taskName=>$taskVal){
            if(array_key_exists($taskName, $existsTasks))
                    continue;
            $task = $auth->createTask($taskName, $taskVal['description']);
            
            
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

}