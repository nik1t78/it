<?php

class FileController extends CController
{
    public function actionUpload()
    {
        $webFolder = '/upload/temp/';
        $tempFolder = Yii::getPathOfAlias('webroot').$webFolder;

        if (!file_exists($tempFolder)) {
            mkdir($tempFolder, 0777, TRUE);
            mkdir($tempFolder.'chunks', 0777, TRUE);
        }

        Yii::import('ext.EFineUploader.qqFileUploader');

        $uploader = new qqFileUploader();
        $uploader->allowedExtensions = array('jpg', 'jpeg', 'png', 'gif', 'doc', 'docx', 'txt', 'pdf','rtf');
        $uploader->sizeLimit = 10 * 1024 * 1024;
        $uploader->chunksFolder = $tempFolder.'chunks';

        $result = $uploader->handleUpload($tempFolder);
        $result['filename'] = $uploader->getUploadName();
        $result['folder'] = $webFolder;
        //$result['folder'] = $tempFolder;

        $uploadedFile = $tempFolder.$result['filename'];

        header("Content-Type: text/plain");
        $result = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
        echo $result;
        Yii::app()->end();
    }

    public function actionDelete()
    {
        if (Yii::app()->request->isAjaxRequest) {
            if (!empty($_POST['src'])) {
                if (file_exists(Yii::getPathOfAlias('webroot').$_POST['src'])){
                    //echo Yii::getPathOfAlias('webroot').$_POST['src'];
                    unlink(Yii::getPathOfAlias('webroot').$_POST['src']);
                }
            }            
        }
    }
}