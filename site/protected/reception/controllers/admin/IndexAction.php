<?php

class IndexAction extends CAction
{
    public function run()
    {
        $model = Reception::model()->findAll();
        $this->controller->render('index', array('model'=>$model));
    }
}
