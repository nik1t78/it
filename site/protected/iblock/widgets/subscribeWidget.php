<?php
Yii::import('iblock.models.*');
//DUrlRulesHelper::import('iblock');

class subscribeWidget extends CWidget
{
    public $iblock;
    public $template = 'form';

    public function run()
    {
        $model = new SubscribeForm();
        $this->render($this->template, array('model'=>$model));
    }
}
