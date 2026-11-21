<?php
Yii::import('banner.models.*');

class bannerWidget extends CWidget
{
	public $position;
        public $template = 'bannerPosition';
        public $htmlOptions=array();
        
	public function init()
	{
            //Получаем запись из БД для указанного прайса. 
            $criteria = new CDbCriteria;
            $criteria->condition = 'code=:code';
            $criteria->params = array(
                ':code' => $this->position
            );
            $pos = BannerPosition::model()->find($criteria);
            if($pos == null) return false;
            
            $banners = $pos->banners;
            $this->render($this->template,array('banners'=>$banners));
	}

	/**
	 * Ends running the widget.
	 */
	public function run()
	{
           //формируем ссылку на файл прайса и выводим ее
            
            
	}

}
