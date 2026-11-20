<?php

Yii::import("ext.EAjaxUpload.qqFileUploader");

class UploadAction extends CAction{
    public function run(){   
        $model = new ContragentUpload;
                        
        $this->controller->render('upload', array('model'=>$model));
        //$this->parseXlsFile();
    }
}