<?php
DUrlRulesHelper::import('iblock');

class MonthlyArchives extends CWidget
{
    public $maxItems = 10;
    public $iblock_id = 0;
    public $section_id = 0;
    
    private $_els = array();
    
    public function init()
    {
        $yearmonth = array();
        
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id AND iblock_section_id=:section_id';
        $criteria->params = array(
            ':iblock_id' => $this->iblock_id,
            ':section_id' => $this->section_id
        );
        $criteria->order = 'timestamp_x DESC';
        
        $els = IBlockElement::model()->active()->findAll($criteria);
        
        $count = 0;
        foreach ($els as $element) {
            $ym = date('F Y', strtotime($element->timestamp_x));
            if (!isset($yearmonth[$ym])) {
                if (++$count > $this->maxItems) break;
                $yearmonth[$ym] = 1;
            } else {
                $yearmonth[$ym]++;
            }
        }
        $this->_els = $yearmonth;
    }
        
    public function run()
    {
        $iblock = IBlock::model()->findByPk($this->iblock_id);
        if($iblock === null) 
            throw new CHttpException(404, Yii::t('iblock', 'Страница не найдена'));
        $iblock_code = $iblock->code;
        $this->render('monthlyArchives', array('els' => $this->_els, 'iblock_code'=>$iblock_code));
    }
}