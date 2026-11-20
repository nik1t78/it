<?php
DUrlRulesHelper::import('faq');

class FaqFormWidget extends CWidget
{
    public $tpl = 'default';
    
    public function run()
    {
        $model = new FaqForm;

        $this->performAjaxValidation($model);
        /*
        if(isset($_POST['FaqForm'])){
            $model->attributes = $_POST['FaqForm'];
            $model->q_date = DateFormat::convertStrToDb($_POST['FaqForm']['q_date'], 'd.m.Y');
            $valid = $model->validate();

            if ($valid) {
                echo CJSON::encode(array(
                    'status' => 'success'
                ));
                Yii::app()->end();
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
        }*/
        $this->render($this->tpl, array('model'=>$model));
    }

    public function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] == 'faq-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
?>
