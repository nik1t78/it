<?php

class UpdateAction extends CAction
{
    public function run ($id)
    {
        $model = ContestStage::model()->findByPk($id);

        if (isset($_POST['ContestStage'])) {
            $model->attributes = $_POST['ContestStage'];

            if ($model->save()) {
                Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('contest', 'Все изменения сохранены.'));
                if (!array_key_exists('applyChanges', $_POST)) {
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }

        $this->controller->render('stage_update', array('model'=>$model));
    }
}