<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class DModuleUrlRulesBehavior extends CBehavior{
    public function events() {
        return array_merge(parent::events(), array(
            'onBeginRequest' => 'beginRequest',
        ));
    }
    
    public function beginRequest(){
        $moduleName = $this->_getModuleName();
        
        if(Yii::app()->hasModule($moduleName)){
            //$module = Yii::app()->getModule($moduleName);
            //if(isset($module->urlRules)){
            //    Yii::app()->getUrlManager()->addRules($module->urlRules);
            //}
            $class = ucfirst($moduleName).'Module';
            Yii::import($moduleName.'.'.$class);
            if(method_exists($class, 'rules')){
                $urlManager = Yii::app()->getUrlManager();
                $urlManager->addRules(call_user_func($class.'::rules'));
            }
        }
    }
    
    protected function _getModuleName(){
        $route = Yii::app()->getRequest()->getPathInfo();
        $domains = explode('/', $route);
        $moduleName = array_shift($domains);
        return $moduleName;
    }
}
?>
