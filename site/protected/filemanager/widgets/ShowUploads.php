<?php

class ShowUploads extends CWidget{
    public $files;
    public $rootDir = 'upload';
    
	public function init(){
		$cs = Yii::app()->clientScript;

		$cs->registerScriptFile(Yii::app()->theme->baseUrl."/js/translateFn.js",CClientScript::POS_END);
		$cs->registerScriptFile(Yii::app()->theme->baseUrl."/js/filemanagerFn.js",CClientScript::POS_HEAD);
		$cs->registerScript('showUploads', '

var csrfToken = "'.Yii::app()->request->csrfToken.'";

$(document).ready(function(){
	var window = $("#window").kendoWindow({
			width: "300px",
            title: "Kendo Window",            
            actions: ["Close"],
            modal: true,
            visible: false
	});
		
	initActions();
	
});
', CClientScript::POS_HEAD);
		
	}
	public function run(){
		//$this->render('showUploads');
		$this->render('showUploads', array('files' => $this->browseDir()));
	}
	
	
    
    public function createDir(){
    	if($_POST['path']){
    		$newPath = $_POST['path'].$_POST['folderName'];
    		//Проверяем начало строки path, если она начинается НЕ с upload, значит пытаются залезть не туда куда надо))
    		if(!$this->checkRootDir($newPath)){
    			echo 'attackError';
    			Yii::app()->end();
    		}
    		if(is_dir($newPath)){
    			echo 'dirExists';
    			Yii::app()->end();
    		}
    		
    		if(!mkdir($newPath)) echo 'createError';
    		else echo 'created';
    		
    		Yii::app()->end();
    	}
    }
    
    public function delete(){
    	$errors = array();
    	if($_POST['elmnts']){
    		$elmntsArray = $_POST['elmnts'];
    		if(is_array($elmntsArray)){
    			foreach($elmntsArray as $item){
    				//Проверяем начало строки path, если она начинается НЕ с upload, значит пытаются залезть не туда куда надо))
    				if(!$this->checkRootDir($item)){
    					echo 'attackError';
    					Yii::app()->end();
    				}
    				
    				if(is_dir($item)){ //Если удаляем директорию - ободим ее рекурсивно и удаляем все содержимое
    					$tmp = $this->recursiveDeleteDir($item);
    					if(count($tmp) > 0){ //есть ошибки при удалении папки
    						$errors = array_merge($errors, $tmp);
    					}
    				}else{ //если удаляем файл - удаляем его
    					if(!unlink($item)){
    						$errors[] = array('type'=>'errorFileDel', 'el'=>$item);
    					}
    				}
    			}
    		}
    		
    		
    	}
    	
    	if(count($errors) > 0)
    		echo json_encode($errors);
    	else
    		echo 'deleted';
    	Yii::app()->end();
    }
    
    
    public function browseDir(){ 
        $files = array();
        if(isset($_POST['path'])) $dir = $_POST['path'];
        else $dir = $this->rootDir . '/';
        
        //Проверяем начало строки path, если она начинается НЕ с upload, значит пытаются залезть не туда куда надо))
    		if(!$this->checkRootDir($dir)){
    			echo 'attackError';
    			Yii::app()->end();
    		}
                
        if(is_dir($dir)){            
            if($dh = opendir($dir)){
                $count = 0;
                while(($file = readdir($dh)) !== false){
                    if($file == '.' || $file == '..') continue; //Пропускаем "." и ".."
                    //if(strpos($file, '.') === 0) continue; //Пропускаем файлы, начинающиеся с точки - это скрытые файлы.
                    
                    $date = Yii::app()->dateFormatter->format('dd MMM yyyy, H:m:s', filemtime($dir.$file));
                    $link = is_dir($dir.$file)?($dir.$file.'/'):($dir.$file);
                    if(filetype($dir.$file) == 'file'){
                        $filename = explode('.', $file);
                        switch(strtolower($filename[count($filename) - 1])){
                            case 'doc':
                            case 'docx':
                                $type = 'doc';
                                break;
                            case 'xls':
                            case 'xlsx':
                            case 'xlsb':
                                $type = 'xls';
                                break;
                            case 'pdf':
                                $type = 'pdf';
                                break;
                            case 'txt':
                                $type = 'txt';
                                break;
                            case 'flv':
                            case 'avi':
                            case 'mp4':
                                $type = 'flv';
                                break;
                            case 'mp3':
                                $type = 'mp3';
                                break;
                            case 'jpg':
                            case 'jpeg':
                                $type = 'jpg';
                                break;
                            case 'gif':
                                $type = 'gif';
                                break;
                            case 'png':
                                $type = 'png';
                                break;
                            case 'swf':
                                $type = 'swf';
                                break;
                            case 'bmp':
                                $type = 'bmp';
                                break;
                            
                            default:
                                $type = 'file';
                                break;
                        }                        
                    }else{
                        $type = 'dir';
                    }
                    
                    
                    $files['items'][] = array('backLink'=>dirname($dir).'/','link'=>$link, 'filename' => $file, 'filesize' => $this->convertFileSize(filesize($dir.$file)), 'time'=>$date, 'perms'=>substr(sprintf('%o', fileperms($dir.$file)), -4), 'type'=>$type);
                    $count++;
                }
                if(sizeof($files) == 0){
                    $files['items'][] = array('backLink'=>dirname($dir).'/','link'=>'', 'filename' => '', 'filesize' => '', 'time'=>'', 'perms'=>'', 'type'=>'');
                }
                $files['count'] = $count;
                closedir($dh);
            }            
        }
        
        return $files;
    }
    
    public function convertFileSize($bytes, $decimals = 2){
        $sz = 'BKMGTP';
        $factor = floor((strlen($bytes)-1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .@$sz[$factor];
        
    }
    
    protected function checkRootDir($path){
    	//Проверяем начало строки path, если она начинается НЕ с upload, значит пытаются залезть не туда куда надо))
    		if(strpos($path, $this->rootDir) > 0 || strpos($path, $this->rootDir) === false || strpos($path, '../') !== false){
    			return false;
    		} else{
    			return true;
    		}
    }
    
    private function recursiveDeleteDir($dir){
    	//$dirContents = glob($dir.'/*');
    	$errors = array();
    	if(is_dir($dir)){
    		if($dh = opendir($dir)){
        		$count = 0;
       			while(($file = readdir($dh)) !== false){
       				if($file == '.' || $file == '..') continue;
            
            		if(is_dir($dir.'/'.$file)){ // Если в тек папке есть подпапки - нужно перейти в них и удалить все их содержимое              			
    					$errors = array_merge($errors, $this->recursiveDeleteDir($dir.'/'.$file));
    				}else{ // Если имеются файлы - удаляем и их    					
    					if(!@unlink($dir.'/'.$file)) {
    						$errors[] = array('type'=>'errorFileDel', 'el'=>$dir.'/'.$file);
    						return $errors;
    					}
    				}
            		
            		$count ++;
            	}
            
        	}
            if(!@rmdir($dir)) $errors[] = array('type'=>'errorDirDel', 'el'=>$dir);
        }else{
        	if(!@unlink($dir)) {
    			$errors[] = array('type'=>'errorFileDel', 'el'=>$dir);
    		}
        }
    	return $errors;
        
    }
    
}
