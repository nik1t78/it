<?php

class ViewAction extends CAction
{
    public function run ($id)
    {
        $model = ContestPage::model()->findByPk($id);

        if ($model == null) {
            throw new CHttpException(404, Yii::t('contest', 'Запрошенная страница не найдена.'));
        }

        $this->controller->bodyPageTitle = $model->title;
        $this->controller->breadcrumbs = array(
            $this->controller->module->name => Yii::app()->createUrl('contest/default/index'),
            $model->contest->title => Yii::app()->createUrl('contest/default/view', array('id'=>$model->contest_id))
        );

        $this->controller->render('viewPage', array('model'=>$model));
    }
}