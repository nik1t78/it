<?php

class DeleteAction extends CAction 
{
    public function run($id)
    {
        if (Yii::app()->request->isPostRequest) {
            $model = Polls::model()->findByPk($id);
            
            if (!$model->delete()) {
                echo Yii::t('system', 'Ошибка при удалении записи');
                Yii::app()->end();
            }
        }
	else{
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
	}
    }
}
