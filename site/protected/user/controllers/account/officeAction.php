<?php

class officeAction extends CAction{
    public function run(){
        $this->controller->sideMenu = array(
            array('label'=>'Настройки', 'url'=>array('/user/account/settings')),
            //array('label'=>'Уведомления', 'url'=>array('/user/account/notifications')),
            array('label'=>'Активные торги', 'url'=>array('/user/account/mybids')),
            array('label'=>'Корзина', 'url'=>array('/user/account/basket')),
            //array('label'=>'История', 'url'=>array('/user/account/history')),            
        );
    	$this->controller->render('officeIndex');  
    }
    
}
