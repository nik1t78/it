<?php
Yii::import('polls.models.*');

class pollWidget extends CWidget
{
    public $poll_id;
    public $htmlOptions = array();
    
    public function init()
    {
        if (!empty($this->poll_id)) {
            $poll = Polls::model()->findByPk($this->poll_id);
            if ($poll == null) return false;
        } else {
            $curr_date = date('Y-m-d H:i:s');
            $criteria = new CDbCriteria;
            $criteria->condition = 'active_from<=:date AND active_to>=:date';
            $criteria->params = array(
                ':date' => $curr_date
            );
            $poll = Polls::model()->active()->find($criteria);
            if ($poll == null) return false;
        }
        
        
        $this->render('pollWidget', array('poll'=>$poll));
    }
}
