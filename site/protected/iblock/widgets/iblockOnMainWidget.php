<?php
Yii::import('iblock.models.*');
DUrlRulesHelper::import('iblock');

class iblockOnMainWidget extends CWidget
{
    public $iblock;
    public $template = 'default';
    public $limit = 3;
    public $htmlOptions=array();
    public $propertyCode;
    public $propertyValue;
    public $condition = false;
    public $crParams = array();
    public $incondition = array();
    public $notincondition = array();
    public $order = 'sort ASC';
    /**
     * Дополнительные параметры, которые передаются в шаблон
     * array(
     *      "paramName" => "paramVar"
     * )
     * @var type array Массив параметров
     */
    public $params = array();


	/**
	 * Ends running the widget.
	 */
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
        $criteria->params = array(
            ':iblock_id' => $iblockM->id
        );

        if ($this->condition) {
            $criteria->addCondition($this->condition);
            $criteria->params = array_merge($criteria->params, $this->crParams);
        }

        if (!empty($this->incondition)) {
            foreach ($this->incondition as $column=>$values) {
                $criteria->addInCondition($column, $values);
            }
        }
        if (!empty($this->notincondition)) {
            foreach ($this->notincondition as $column=>$values) {
                $criteria->addNotInCondition($column, $values);
            }
        }

        $criteria->order = $this->order;
        $criteria->limit = $this->limit;

        $els = IBlockElement::model()->active()->byProperty($this->propertyCode, $this->propertyValue)->findAll($criteria);

        $this->render($iblockM->template . '/' . $this->template, array('elements'=>$els, 'params'=>$this->params));
	}

}
