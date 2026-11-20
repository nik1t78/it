<?php

class IndexAction extends CAction
{
    public function run()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'id ASC';
        $modelCnt = Contest::model()->count($criteria);
        $pages = new CPagination($modelCnt);
        $pages->pageSize = 20;
        $pages->applyLimit($criteria);
        $model = Contest::model()->findAll($criteria);

        $this->controller->render('index', array('model' => $model, 'pages' => $pages));
    }
}