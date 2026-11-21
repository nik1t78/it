<?php

class CreateAction extends CAction
{
    public function run()
    {
        $model = new Contest;

        if (isset($_POST['Contest'])) {
            $model->attributes = $_POST['Contest'];

            if ($model->save()) {
                Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('contest', 'Конкурс "'.$model->title.'" успешно создан.'));
                if (!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(array('/contest/admin/index'));
            }
        }
        $this->controller->render('create', array('model' => $model));
    }
}