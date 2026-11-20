<?php

Yii::import('iblock.models.*');

class PublishAction extends CAction
{
    public function run($id)
    {
        if (Yii::app()->request->isAjaxRequest){
            $model = IBlockElement::model()->findByPk($id);
            if ($model == null) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Запрошенный элемент не найден.'
                ));
                Yii::app()->end();
            }

            $model->published = IBlockElement::ACTIVE;
            if ($model->save(false)) {
                echo json_encode(array(
                    'status' => 'success'
                ));
                Yii::app()->end();
            }

            echo json_encode(array(
                'status' => 'error',
                'message' => 'При сохранении статуса элемента возникла ошибка.'
            ));
            Yii::app()->end();


        } else {
            throw new CHttpException(400, Yii::t('system', 'Неверный запрос. Пожалуйста, повторите попытку позже.'));
        }
    }
}
