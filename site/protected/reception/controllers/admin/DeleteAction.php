<?php

class DeleteAction extends CAction
{
    public function run($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $model = Reception::model()->findByPk($id);
            
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
            
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
		$this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/backend/iblock/'));/****/
        }
	else{
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
	}
    }
}