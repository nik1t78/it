<?php

class IndexAction extends CAction{
    public function run(){
    	$this->controller->layout = '//layouts/index-special';

        $this->controller->render('index');
    }
}
