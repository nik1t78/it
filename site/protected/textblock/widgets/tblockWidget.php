<?php
Yii::import('textblock.models.Textblock');

class tblockWidget extends CWidget
{
	public $tblock;
        public $htmlOptions=array();
        public $template = 'textBlock';
        
	public function init()
	{
            //Получаем запись из БД для указанного прайса. 
            $criteria = new CDbCriteria;
            $criteria->condition = 'code=:code';
            $criteria->params = array(
                ':code' => $this->tblock
            );
            $textBlock = Textblock::model()->find($criteria);
            if($textBlock == null) return false;
            
            $anons = '';
            if(strlen(strip_tags($textBlock->body)) > 340)
                $anons = substr($textBlock->body, 0, 340).'<span class="moreInLine">»</span>';
            else
                $anons = strip_tags($textBlock->body);
            
            $this->render($this->template,array('model'=>$textBlock, 'anons'=>$anons));
	}

	/**
	 * Ends running the widget.
	 */
	public function run()
	{
           //формируем ссылку на файл прайса и выводим ее
            
            
	}

}
