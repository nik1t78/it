<?php
Yii::import('iblock.models.*');
DUrlRulesHelper::import('iblock');

class relativeElementsWidget extends CWidget
{
    public $iblock;
    public $template = 'default';
    public $relateAttr = 'id';
    public $relateVal = 0;
    public $order = 'sort ASC';

    public function run()
    {
        $criteria = new CDbCriteria();
        if (is_string($this->iblock)) {
            $iblockM = IBlock::model()->findByAttributes(array('code'=>$this->iblock));
        } elseif (is_int($this->iblock)) {
            $iblockM = IBlock::model()->findByPk($this->iblock);
        }
        if ($iblockM == null) {
            return false;
        }
        $criteria->condition = 't.iblock_id = :iblock_id';

        $criteria->addCondition('t.iblock_section_id IN (SELECT id FROM {{iblock_section}} s WHERE s.'.$this->relateAttr.' = :relval)');
        $criteria->params = array(
            ':iblock_id' => $iblockM->id,
            ':relval' => $this->relateVal
        );

        $criteria->order = $this->order;

        $els = IBlockElement::model()->active()->findAll($criteria);

        $this->render($iblockM->template . '/' . $this->template, array('elements'=>$els));
    }
}
