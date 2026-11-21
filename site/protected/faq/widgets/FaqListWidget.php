<?php

class FaqListWidget extends CWidget
{
    public $limit = 3;

    public function run()
    {
        $criteria = new CDbCriteria;
        $criteria->limit = $this->limit;
        $criteria->order = 'q_date DESC';

        $questions = Faq::model()->approved()->findAll($criteria);

        $this->render('list', array('questions'=>$questions));
    }
}
?>
