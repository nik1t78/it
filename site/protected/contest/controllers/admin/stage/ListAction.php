<?php

class ListAction extends CAction
{
    public function run($id)
    {
        Yii::app()->user->returnUrl = Yii::app()->request->requestUri;

        $contest = Contest::model()->findByPk($id);
        $criteria = new CDbCriteria;
        $criteria->condition = 'contest_id=:contest_id';
        $criteria->params = array(
            ':contest_id' => $id
        );
        $model = ContestStage::model()->findAll($criteria);

        $this->controller->render('stage_list', array('model' => $model, 'contest'=>$contest));
    }
}