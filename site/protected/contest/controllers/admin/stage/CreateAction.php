<?php

class CreateAction extends CAction
{
    public function run ($id)
    {
        $model = new ContestStage;
        $model->contest_id = $id;

        if (isset($_POST['ContestStage'])) {
            $model->attributes = $_POST['ContestStage'];

            if ($model->save()) {
                Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('contest', 'Этап успешно добавлен.'));
                if (!array_key_exists('applyChanges', $_POST)) {
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        $this->controller->render('stage_create', array('model'=>$model));
    }
}