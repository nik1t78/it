<?php

class BackAccessControl extends CFilter{
    public function preFilter($filterChain){
        if(Yii::app()->user->checkAccess('administrator')){
            return true;
        }
        $filterChain->controller->redirect(array(Yii::app()->user->loginUrl));
    }
}