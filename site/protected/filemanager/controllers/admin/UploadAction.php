<?php

Yii::import("ext.EAjaxUpload.qqFileUploader");

class UploadAction extends CAction{
    public $rootDir = 'upload';
    
    public function run(){ 
    	$folder = !empty($_GET['path']) ? $_GET['path'] : 'upload/';
    	
    	if(!$this->checkRootDir($folder)){
    		echo 'attackError';
    		Yii::app()->end();
    	}
    		
    	//$allowedExtensions = array("jpg"); 
    	$sizeLimit = $this->controller->module->maxFileSize;
    	$uploader = new qqFileUploader($this->controller->module->allowedExtensions, $sizeLimit);
    	$result = $uploader->handleUpload($folder);
    	
    	$return = htmlspecialchars(json_encode($result), ENT_NOQUOTES);
    	
    	$fileSize = filesize($folder.$result['filename']);
    	$fileName = $result['filename'];
    	
    	echo $return;
    	
    	/**
        $path = Yii::getPathOfAlias('webroot');
        $model=new Filemanager;
        
        if(isset($_POST['Filemanager'])){
            $model->attributes=$_POST['Filemanager'];
            $model->uploadfiles=CUploadedFile::getInstances($model,'uploadfiles');
            VarDumper::dump($model->uploadfiles);
            
            if(is_array($model->uploadfiles)){
                
        //    if($model->save()){
                foreach($model->uploadfiles as $file){
                    //$file->saveAs('/'.$model->uploadpath);
                    
                    if(!$file->saveAs($path.'/'.$model->uploadpath.$file->name))
                    {
                        echo (Yii::t('yiiext','Cannot save file "{filepath}".',array('{filepath}'=>$path.'/'.$model->uploadpath.''.$file->name)));
                        return;
                    }
                }

                //$model->image->saveAs('path/to/localFile');
                // перенаправляем на страницу, где выводим сообщение об
                // успешной загрузке
        //    }
            }else{
                if(!$model->uploadfiles->saveAs($path.'/'.$model->uploadpath.$model->uploadfiles->name))
                {
                    echo (Yii::t('yiiext','Cannot save file "{filepath}".',array('{filepath}'=>$path.'/'.$model->uploadpath.$model->uploadfiles->name)));
                    return;
                }
            }
        }
        //$this->render('create', array('model'=>$model));
        **/
    }
    
    protected function checkRootDir($path){
    	//Проверяем начало строки path, если она начинается НЕ с upload, значит пытаются залезть не туда куда надо))
    		if(strpos($path, $this->rootDir) > 0 || strpos($path, $this->rootDir) === false || strpos($path, '../') !== false){
    			return false;
    		} else{
    			return true;
    		}
    }
}