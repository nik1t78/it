<?php

/**
 * Created by PhpStorm.
 * User: covax
 * Date: 24.09.16
 * Time: 11:08
 */
class AjaxAction extends CAction
{
    public function run()
    {
        if (!Yii::app()->request->isAjaxRequest) {
            $this->controller->redirect('/backend/iblock/');
        }

        if (!empty($_POST['action']) && $_POST['action'] == 'addValue') {
            $this->addValue();
        }
    }

    protected function addValue()
    {
        if (!empty($_POST['propCode']) && !empty($_POST['propValue'])) {
            $propCode = $_POST['propCode'];
            $propValue = $_POST['propValue'];

            $propModel = IBlockProperty::model()->find('code=:code', array(':code'=>$propCode));
            if ($propModel == null) {
                echo json_encode(array('error' => 'Свойство не найдено.'));
                Yii::app()->end();
            }

            $propTarget = IBlockPropertyEnum::model()->count('iblock_property_id=:property_id AND value=:value', array(
                ':property_id' => $propModel->id,
                ':value' => $propValue
            ));
            if ($propTarget > 0) {
                echo json_encode(array('error' => 'Такое значение уже существует'));
                Yii::app()->end();
            }

            $newValue = new IBlockPropertyEnum();
            $newValue->iblock_property_id = $propModel->id;
            $newValue->value = $propValue;

            if ($newValue->save()) {
                echo json_encode(array('message' => 'Новое значение создано', 'id' => $newValue->id));
                Yii::app()->end();
            } else {
                echo json_encode(array('error' => 'При сохранении возникла ошибка.'));
                Yii::app()->end();
            }
        }
    }
}
