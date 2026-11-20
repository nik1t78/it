<?php

class sendOrderAction extends CAction{
    private $goods = array();
    private $products = array();
    private $totalPrice;
    
    public function run(){
        if(Yii::app()->user->isGuest){
            $this->controller->redirect('/');
        }
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
        $this->sendOrder(); 
    }
    
    private function sendOrder(){
        if(!isset(Yii::app()->request->cookies['manager']) || !isset(Yii::app()->request->cookies['basket'])){
            //куки пустые - заказ не оформляем, выводим ошибку
            
            
            $this->controller->render('errorOrder'); 
            return false;
        }
        
        $adminEmail = Yii::app()->params->adminEmail; //Email менеджера, берется из настроек сайта
        $title = 'Заказ с сайта '.Yii::app()->request->hostInfo; //Заголовок письма, берется из настроек сайта
                
        $user = User::model()->findByPk(Yii::app()->user->getState('id'));
        
        $managerId = Yii::app()->request->cookies['manager']->value;
        if($managerId != null){
            $manager = User::model()->findByPk($managerId);
        }
        
        $this->getBasketEntries(); //получаем из куков содержимое корзины и сохраняем в бд
        
        //создаем заказ
        $order = new Order;
        $order->user_id = $user->id;
        $order->amount = $this->totalPrice;
        $order->status = Order::NOT_PAID;
        $order->manager_id = $manager->id;
        $order->products = $this->products;
        $order->save();
        
        
        $emailer = new EmailMessenger;
        $emailer->from = $adminEmail;
        $emailer->copy = Yii::app()->params->zakazEmail;
        $emailer->send($manager->email, $title, $this->controller->renderPartial('emailManagerSendOrder', array('user'=>$user, 'manager'=>$manager, 'goods'=>$this->goods, 'totalPrice'=>$this->totalPrice), true)); //письмо менеджеру
        $emailer->copy = null;
        $emailer->send($user->email, $title, $this->controller->renderPartial('emailUserSendOrder', array('user'=>$user, 'manager'=>$manager, 'goods'=>$this->goods, 'totalPrice'=>$this->totalPrice), true)); //письмо покупателю
    
        unset(Yii::app()->request->cookies['basket']);
        unset(Yii::app()->request->cookies['manager']);
    	$this->controller->render('sendOrder'); 
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
            $price = (float)str_replace(',', '.', $priceSrc);
            
            $this->goods[] = array('id'=>$model->id, 'parent'=>$model->section_id, 'title'=>$model->name, 'price'=>$price, 'countOrder'=>$product->cnt);
            
            $tmpModel = new OrderProduct;
            $tmpModel->product_id = $model->id;
            $tmpModel->count = $product->cnt;
            $tmpModel->price = $price;
            
            $this->products[] = $tmpModel;
            
            $this->totalPrice += ($price * $product->cnt);
        }
        
    }
}
