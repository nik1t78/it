<?php

class historyAction extends CAction{
    public function run(){
        $this->controller->sideMenu = array(
            array('label'=>'Настройки', 'url'=>array('/user/account/settings')),
            //array('label'=>'Уведомления', 'url'=>array('/user/account/notifications')),
            array('label'=>'Активные торги', 'url'=>array('/user/account/mybids')),
            array('label'=>'Корзина', 'url'=>array('/user/account/basket')),
            //array('label'=>'История', 'url'=>array('/user/account/history')),            
        );
        
        $criteria = new CDbCriteria;
        $criteria->condition = 'user_id=:user_id';
        $criteria->params = array(':user_id'=>Yii::app()->user->id);
        $orders = Order::model()->findAll($criteria);
        
        $products = array();
        foreach($orders as $order){
            foreach($order->elements as $product){
                $orderProduct = OrderProduct::model()->find('order_id=:order_id and product_id=:product_id', array(':order_id'=>$order->id, ':product_id'=>$product->id));
                if($orderProduct == null) continue;
                $products[$order->id][] = array(
                    'name' => $product->name,
                    'count' => $orderProduct->count,
                    'price' => $orderProduct->price,
                );
            }
        }
        
    	$this->controller->render('history', array('orders'=>$orders, 'products' => $products));  
    }
    
}
