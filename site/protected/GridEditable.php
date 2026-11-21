<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
Yii::import('zii.widgets.grid.CGridView');

class GridEditable extends CGridView{
    public function init(){
        parent::init();
    }
    
    public function renderTableRow($row) {
        parent::renderTableRow($row);
    }
}
?>
