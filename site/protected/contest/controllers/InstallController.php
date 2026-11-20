<?php

class InstallController extends BackendController
{
    public $defaultAction = 'index';
    public $menu = array();
    public $layout = '//layouts/column1';

    public function init()
    {
        parent::init();
    }

    public function actionIndex()
    {
        $this->render('index');
        $this->installPermission();
    }

    protected function installPermission()
    {
        $permission = $this->module->modulePermission();
        $auth = Yii::app()->authManager;
        $existsOperations = $auth->operations;
        $existsTasks = $auth->tasks;

        foreach ($permission as $taskName=>$taskVal) {
            if (array_key_exists($taskName, $existsTasks))
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