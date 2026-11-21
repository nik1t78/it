<?php

Yii::import('contest.models.*');
class DeleteAction extends CAction
{
    public function run($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $model = ContestMember::model()->findByPk($id);

            if (!$model->delete()) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'При удалении записи возникла ошибка.'
                ));
                Yii::app()->end();
            } else {
                echo json_encode(array(
                    'status' => 'success'
                ));
                Yii::app()->end();
            }

            if (!isset($_GET['ajax']))
                $this->controller->redirect (isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/backend/contest/'));
        } else {
            throw new CHttpException(500, Yii::t('system', 'Неверный запрос. Пожалуйста, повторите попытку позже.'));
        }
    }
}