<?php

class AjaxAction extends CAction
{
    public function run()
    {
        $model = new SubscribeForm();
        $this->controller->performAjaxValidation($model);

        if (!empty($_POST['SubscribeForm'])) {
            $model->attributes = $_POST['SubscribeForm'];

            if ($model->validate()){

                if (!is_array($model->iblock_id)) {
                    $model->iblock_id = array($model->iblock_id);
                }
                foreach ($model->iblock_id as $iblock_id) {
                    $subscribe = new IBlockSubscribe();

                    $subscribe->iblock_id = $iblock_id;
                    $subscribe->email = $model->email;
                    $subscribe->save();
                }

                echo CJSON::encode(array('status'=>'success'));
                Yii::app()->end();
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
        }
        Yii::app()->end();
    }
}
