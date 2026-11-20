<?php

class IndexAction extends CAction{
    public $files;
    public $rootDir = 'upload';
    
    public function run(){
        
    	$fileType = '';
    	
    	if(isset($_GET['Type']) && !empty($_GET['Type'])){
	    	$fileType = $_GET['Type'];
    	} else if((isset($_POST['Type']) && !empty($_POST['Type']))){
	    	$fileType = $_POST['Type'];
    	}
    
        //Читаем содержимое папки upload
        if(Yii::app()->request->isAjaxRequest){
            if(isset($_POST['eventType'])){
                $eventType = $_POST['eventType'];
                
                switch($eventType){
                    case "browse":                        
                        $files = $this->controller->browseDir($fileType);
                        echo json_encode($files);
                        break;
                    
                    case "createDir":
                        $this->controller->createDir();
                        break;
                                        
                    case "delete":
                        $this->controller->delete();
                        break;
                    
                    case "saveItem":
                        $this->controller->saveItem();
                        break;
                        
                    default:
                        echo "Error. Not requested action.";
                        break;
                }
            }
            
			Yii::app()->end();
        }
                
        $this->controller->render('index');
    }
    
}