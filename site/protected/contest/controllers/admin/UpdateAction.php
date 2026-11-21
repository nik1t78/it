<?php

class UpdateAction extends CAction
{
    public function run($id)
    {
        $model = Contest::model()->findByPk($id);

        if (isset($_POST['Contest'])) {
            $model->attributes = $_POST['Contest'];

            if ($model->save()) {
                Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('contest', 'Все изменения сохранены.'));
                if (!array_key_exists('applyChanges', $_POST)) {
                    $this->controller->redirect(array('/contest/admin/index'));
                }
            }
        }

        $this->controller->render('update', array('model' => $model)); 
    }
}