<?php

class BrowserAction extends CAction{
    public $files;
    public $rootDir = 'upload';
    
    public function run(){
    	$this->controller->layout = '//layouts/minimum';
        $this->controller->render('browser');
    }
    
}