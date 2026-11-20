<?php

class CreateAction extends CAction
{
    public function run($id)
    {
        $model = new ContestMember;
        $model->contest_id = $id;

        if (isset($_POST['ContestMember'])) {
            $model->attributes = $_POST['ContestMember'];

            if ($model->save()) {
                Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('contest', 'Участник "'.$model->title.'" успешно добавлен.'));
                if (!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(Yii::app()->user->returnUrl);
            }
        }
        $this->controller->render('member_create', array('model' => $model));
    }
}