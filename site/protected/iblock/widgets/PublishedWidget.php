<?php

class PublishedWidget extends CWidget
{

    public function run()
    {
        if (!Yii::app()->user->checkAccess('publishIBlock')) {
            return false;
        }
        
        $criteria = new CDbCriteria;
        $criteria->condition = 'published=:published';
        $criteria->params = array(
            ':published'=>  IBlockElement::NON_ACTIVE
        );
        $criteria->order = 'iblock_id ASC, active_from DESC';
       
        $elements = IBlockElement::model()->findAll($criteria);

        $this->render('published', array('elements'=>$elements));
    }
}