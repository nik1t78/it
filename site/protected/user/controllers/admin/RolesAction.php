<?php

class RolesAction extends CAction{
    private $_auth;
    
    public function run(){        
        $this->_auth = Yii::app()->authManager;
        
        $roles = $this->_auth->roles;
        $tasks = $this->_auth->tasks;
        //$operations = $this->_auth->operations;
        
        $items = $this->getStructure($tasks);
        
        $this->controller->render('roles', array(
            'roles' => $roles,
            'items' => $items
        ));
    }
    
    private function getStructure($items){
        $tmp = array();
        foreach($items as $name=>$val){
            $tmp[$name] = array(
                'description' => $val->description,
                'bizRule' => $val->bizRule,
                'items' => $this->getStructure($this->_auth->getItemChildren($name))
            );
        }
        return $tmp;
    }
}