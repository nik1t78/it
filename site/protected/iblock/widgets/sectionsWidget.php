<?php
Yii::import('iblock.models.*');
DUrlRulesHelper::import('iblock');

class sectionsWidget extends CWidget
{
    public $iblock;
    public $template = 'default';
    public $limit = 5;
    public $order = 'sort ASC';
    public $activeItem = '';

    public function run()
    {
        $criteria = new CDbCriteria();
        if (is_string($this->iblock)) {
            $iblockModel = IBlock::model()->findByAttributes(array('code'=>$this->iblock));
        } elseif (is_int($this->iblock)) {
            $iblockModel = IBlock::model()->findByPk($this->iblock);
        }
        if ($iblockModel == null) {
            return false;
        }

        $criteria->condition = 't.iblock_id=:iblock_id';
        $criteria->params = array(
            ':iblock_id' => $iblockModel->id
        );
        $criteria->order = $this->order;
        $criteria->limit = $this->limit;

        $sections = IBlockSection::model()->active()->findAll($criteria);

        $this->render($iblockModel->template . '/' . $this->template, array('sections'=>$sections, 'activeItem' => $this->activeItem));

    }
}
