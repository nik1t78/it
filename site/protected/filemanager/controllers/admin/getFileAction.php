<?php

class getFileAction extends CAction{
    public $files;
    public $rootDir = 'upload';
    
    public function run(){
    	$this->controller->layout = '//layouts/minimum';
        $this->controller->render('getFile');
    }
    
}