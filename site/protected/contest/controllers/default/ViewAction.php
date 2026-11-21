<?php

class ViewAction extends CAction
{
    public function run ($id, $nid = 0)
    {
        $model = Contest::model()->findByPk($id);
        $nomination = null;

        if ($model == null) {
            throw new CHttpException(404, Yii::t('contest', 'Указанный конкурс не найден.'));
        }

        $this->controller->bodyPageTitle = $model->title;
        $this->controller->breadcrumbs = array(
            $this->controller->module->name => Yii::app()->createUrl('contest/default/index'),
            $model->title
        );

        $criteria = new CDbCriteria;
        $criteria->condition = 'contest_id=:contest_id AND active="Y"';
        $criteria->params = array(
            ':contest_id' => $id
        );

        if ($nid > 0) {
            $nomination = ContestNomination::model()->findByPk($nid);
            if ($nomination){
                $criteria->addCondition('nomination_id=:nomination');
                $criteria->params[':nomination'] = $nid;
            }
        }
        $criteria->order = 'vote DESC, id ASC';
        $modelCnt = ContestMember::model()->count($criteria);
        $pages = new CPagination($modelCnt);
        $pages->pageSize = 18;
        $pages->applyLimit($criteria);
        $members = ContestMember::model()->findAll($criteria);
        
        $this->controller->render('view', array('model' => $model, 'members' => $members, 'pages' => $pages, 'currNomination' => $nomination));
    }
}