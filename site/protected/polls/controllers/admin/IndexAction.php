<?php

class IndexAction extends CAction
{
    public function run()
    {
        $model = Polls::model()->findAll();
        $this->controller->render('index', array('model'=>$model));
    }
}