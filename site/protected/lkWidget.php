<?php

class LkWidget extends CWidget{
	public $auctionsCount = 0; // ТУТ МЫ ДОЛЖНЫ ПОЛУЧИТЬ КОЛИЧЕСТВО АУКЦИОНОВ, В КОТОРЫХ УЧАСТВУЕТ ПОЛЬЗОВАТЕЛЬ
        public $basketCount = 0; //КОЛИЧЕСТВО ТОВАРОВ В КОРЗИНЕ

	public function init(){
            if(Contragent::getContragentByUserId(Yii::app()->user->id)->confirm == Contragent::CONFIRM){
                //Если пользователь не заблокирован - вычисляем кол-во заказов и ставок. Если заблокирован - возвращаем нули
		$this->getBasketCount();

                $criteria = new CDbCriteria;
                $criteria->condition = 'user_id=:userId';
                $criteria->params = array(':userId' => Yii::app()->user->id);
                $criteria->group = 'lot_id';

                $bids = AuctionBid::model()->findAll($criteria);
                //VarDumper::dump($bids);

                $auctionsArr = array();
                foreach($bids as $bid){
                	$lot = AuctionLot::model()->findByPk($bid->lot_id);
                	$auction = Auction::model()->findByPk($lot->auction_id);
                        if($auction == null){
	                	continue;
                	}
                	//VarDumper::dump($bid->lot->id.' - '.$lot->id);
                	//VarDumper::dump('auction to: '.$bid->auction->active_to.' curr: '.date('Y-m-d H:i:s'));
                    if($auction->active_to >= date('Y-m-d H:i:s'))
                        $auctionsArr[] = $auction->id;
                }

                $this->auctionsCount = count($auctionsArr);
            }
		$this->render('lkWidget', array('auctionsCount' => $this->auctionsCount,'basketCount' => $this->basketCount));
	}

	public function run(){

	}

        public function getBasketCount(){
            $basket = (isset(Yii::app()->request->cookies['basket']->value)) ?
                json_decode(Yii::app()->request->cookies['basket']->value) : array();


            //$basket = (isset($_COOKIE['basket'])) ?
            //    json_decode($_COOKIE['basket']) : NULL;

            $this->basketCount = count($basket);
        }
}
