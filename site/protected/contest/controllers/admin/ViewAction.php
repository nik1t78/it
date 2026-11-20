<?php

class ViewAction extends CAction
{
    public function run($id)
    {
        Yii::app()->user->returnUrl = Yii::app()->request->requestUri;

        $criteria = new CDbCriteria;
        $criteria->condition = 'contest_id=:contest_id';
        $criteria->params = array(
            ':contest_id' => $id
        );
        $modelCnt = ContestMember::model()->count($criteria);
        $pages = new CPagination($modelCnt);
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);
        $model = ContestMember::model()->findAll($criteria);

        $this->controller->render('view', array('model' => $model,'pages' => $pages, 'contest_id'=>$id));
    }
}