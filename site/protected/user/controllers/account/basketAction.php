<?php

class basketAction extends CAction{
    private $goods = array();
    private $totalPrice;
    
    public function run(){
        $this->controller->sideMenu = array(
            array('label'=>'Настройки', 'url'=>array('/user/account/settings')),
            //array('label'=>'Уведомления', 'url'=>array('/user/account/notifications')),
            array('label'=>'Активные торги', 'url'=>array('/user/account/mybids')),
            array('label'=>'Корзина', 'url'=>array('/user/account/basket')),
            //array('label'=>'История', 'url'=>array('/user/account/history')),            
        );
        $cs=Yii::app()->getClientScript();                
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.json-2.3.min.js');
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/jquery.cookie.js');
        $cs->registerScriptFile(Yii::app()->baseUrl.'/js/catalogFn.js');
        $this->getBasketEntries();
        
        $managers = array(0=>'не выбрано');
        $managerModel = User::model()->findAll('role=:role', array(':role'=>User::ROLE_MANAGER));
        foreach($managerModel as $manager){
            $managers[$manager->id] = $manager->full_name;
        }
    	$this->controller->render('basketIndex', array('goods'=>$this->goods, 'totalPrice' => number_format($this->totalPrice, 2, ',', ' '), 'managers'=>$managers));  
    }
    
    private function getBasketEntries(){
        $basket = (isset(Yii::app()->request->cookies['basket']->value)) ?
                json_decode(Yii::app()->request->cookies['basket']->value) : array();
            
        
        //$basket = (isset($_COOKIE['basket'])) ?
        //    json_decode($_COOKIE['basket']) : NULL;
        foreach($basket as $product){
            $model = CatalogElement::model()->findByPk($product->id);
            if($model == null) 
                throw new CHttpException(400, Yii::t('catalog', 'Товары, добавленные в корзину, в Базе данных не найдены. Возможно произошла какая-то ошибка.'));
            
            $propPrice = null;
            $props = $model->properties;
            foreach($props as $property){
                $modelP = CatalogProperty::model()->findByPk($property->property_id);
                if($modelP->code == 'price')
                    $propPrice = $property->value;
            }
            $priceSrc = $model->price == null ? $propPrice : $model->price;
            //$price = (float)str_replace(',', '.', $priceSrc);
            $price = number_format($priceSrc, 2, ',', ' ');
            $summ = $priceSrc*$product->cnt;
            $summ = number_format($summ, 2, ',', ' ');
            $this->goods[] = array('id'=>$model->id, 'title'=>$model->name, 'price'=>$price, 'summ'=>$summ, 'countOrder'=>$product->cnt);
            
            $this->totalPrice += ($priceSrc * $product->cnt);
        }
        
    }
}
