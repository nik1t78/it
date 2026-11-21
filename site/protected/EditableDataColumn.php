<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('zii.widgets.grid.CDataColumn');

class EditableDataColumn extends CDataColumn{
    
    public function init(){
        parent::init();
    }
    
    /**
	 * Renders the data cell content.
	 * This method evaluates {@link value} or {@link name} and renders the result.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
    protected function renderDataCellContent($row,$data)
    {
	if($this->value!==null)
            $value=$this->evaluateExpression($this->value,array('data'=>$data,'row'=>$row));
	else if($this->name!==null)
            $value='test'.CHtml::value($data,$this->name);
	echo $value===null ? $this->grid->nullDisplay : $this->grid->getFormatter()->format($value,$this->type);
    }
}
?>
