<?php

class myBidsAction extends CAction{
    
    public function run(){
        $currLots = array();
        if(Yii::app()->request->isAjaxRequest){
            $currLots = $this->getMyLots(Yii::app()->user->id);
            echo json_encode($currLots);
        }else{
            if(Contragent::getContragentByUserId(Yii::app()->user->id)->confirm == Contragent::CONFIRM){
                $this->controller->sideMenu = array(
                    array('label'=>'Настройки', 'url'=>array('/user/account/settings')),
                    //array('label'=>'Уведомления', 'url'=>array('/user/account/notifications')),
                    array('label'=>'Активные торги', 'url'=>array('/user/account/mybids')),
                    array('label'=>'Корзина', 'url'=>array('/user/account/basket')),
                    //array('label'=>'История', 'url'=>array('/user/account/history')),            
                );

                $currLots = $this->getMyLots(Yii::app()->user->id);
            }
            $this->controller->render('myBidsList', array('currLots'=>$currLots)); 
        }
    }
    
    private function getMyLots($user_id){
            $criteria = new CDbCriteria;
            $criteria->condition = 'user_id=:org_id';
            $criteria->params = array(':org_id'=>Yii::app()->user->id);
            $criteria->having = 'MAX(value)';
            $criteria->group = 'lot_id';

            $bids = AuctionBid::model()->findAll($criteria);
            $currLots = array();

            foreach($bids as $bid){
                $lot = AuctionLot::model()->findByPk($bid->lot_id);
                $auction = Auction::model()->findByPk($lot->auction_id);

                if($auction->active_to < date('Y-m-d H:i:s'))
                    continue;


                $propArr = array();
                foreach ($lot->products->properties as $prop){
                    $propArr[$prop->property->code] = array('name'=>$prop->property->name, 'value'=>$prop->value);
                }
                $cr = new CDbCriteria;
                $cr->condition = 'user_id=:org_id and lot_id=:lot_id';
                $cr->params = array(':org_id'=>$bid->user_id, ':lot_id'=>$bid->lot_id);
                $cr->order = 'value DESC';
                $cr->limit = '0,1';
                $tmp_model = AuctionBid::model()->find($cr);
                $my_bid = $tmp_model->value;
                $section = AuctionSection::model()->findByPk($lot->products->section_id);
                $currLots[] = array(
                    'lot_id' => $lot->id,
                    'lot_name' => $section->name.' - '.$lot->products->name,
                    'count' => $propArr['count']['value'],
                    'my_bid' => $my_bid,
                    'max_bid' => $lot->lastBid->value,
                    'you_win' => $my_bid === $lot->lastBid->value,
                    'total_price' => $propArr['count']['value'] * $lot->lastBid->value,
                    'active_to' => DateFormat::convertDbToStr($auction->active_to, 'd.m.Y H:i:s'),
                    'active' => $lot->active,
                );
            }
            return $currLots;
    }
}
