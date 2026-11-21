<?php

class IndexAction extends CAction{     
    public function run(){   
    	$this->controller->layout = '//layouts/index';
        $this->controller->render('index');
    }
}