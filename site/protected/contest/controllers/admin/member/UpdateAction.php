<?php

class UpdateAction extends CAction
{
    public function run($id)
    {
        $model = ContestMember::model()->findByPk($id);

        if (isset($_POST['ContestMember'])) {
            $model->attributes = $_POST['ContestMember'];

            if ($model->save()) {
                Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('contest', 'Все изменения сохранены.'));
                if (!array_key_exists('applyChanges', $_POST)) {
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }

        $groups = ContestParamGroup::model()->findAll('contest_id=:contest_id', array(':contest_id' => $model->contest_id));

        $this->controller->render('member_update', array(
            'model' => $model,
            'groups' => $groups,
        ));
    }
}