<?php

class AjaxAction extends CAction{
    /**
     * Контроллер, обрабатывающий события вызванные ajax-запросами
     **/
    public function run(){
        //если запрос асинхронный, то нам нужно отдать только данные
        
        if(Yii::app()->request->isAjaxRequest){
            if (isset($_POST['page_id'])) {
                $page_id = $_POST['page_id'];
                $model = Pages::model()->findByPk($page_id);
                if ($model === null) {
                    echo json_encode(array('error' => 'Указанная страница не найдена.'));
                } else {
                    $result = $model->attributes;
                    $result['url'] = $model->getUrlByPage($page_id);
                    echo json_encode($result);
                }
                Yii::app()->end();
            }

            if(isset($_POST['eventType'])){
                $eventType = $_POST['eventType'];
                
                switch($eventType){
                    case "updateTree":
                        $this->updateTree();
                        break;
                    
                    case "createItem":
                        $this->createItem();
                        break;
                    
                    case "saveItem":
                        $this->saveItem();
                        break;
                        
                    default:
                        echo "Error. Not requested action.";
                        break;
                }
            }
            Yii::app()->end();
        }else{
            //если запрос не асинхронный, редиректим в корень модуля
            $this->controller->redirect('/backend/pages');
        }
    }
    
    /**
     * Функция сохранения изменений в структуре сайта. Вызывается Ajax-запросом
     **/
    public function updateTree(){                   
        $treeItems = json_decode($_POST['treeItems']);
            
        foreach($treeItems as $item){
       	    $item = get_object_vars($item);
            $model = Pages::model()->findByPk((int) $item['id']);
            if($model===null) {
            		//$this->getController()->redirect(array($this->getController()->CQtreeGreedView['adminAction']));
          		echo 'Не найдена запись с указанным Id';
          		return false;
      		}
        		
      		unset($item['id']); //при обновлении записи не нужно переписывать ее id
        		
                //VarDumper::dump($model->attributes);
                //VarDumper::dump(get_object_vars($item));
         	$model->attributes = $item;
                //VarDumper::dump($model->attributes);
                
         	if($model->saveNode()) {
                	//$this->getController()->redirect(array($this->getController()->CQtreeGreedView['adminAction']));
                echo 'ok';                	
         	}
        }            
    }
    
    /**
     * Функция создания элемента структуры сайта, которая вызывается ajax-запросом
     **/
    public function createItem(){ 
        $model=new Pages;
		$this->controller->performAjaxValidation($model);
                
        $this->controller->renderPartial('create',array(
			'model'=>$model,
		));
    }
	
	/**
	 * Функция сохранения элемента структуры
	 **/
	public function saveItem(){
		if(!empty($_POST['formFields'])){
			$formFields = json_decode($_POST['formFields']);
		
			$newItemFields = array();
		
			foreach($formFields as $field){
				$field = get_object_vars($field);
				
				if($field['name'] == 'YII_CSRF_TOKEN' || $field['name'] == 'el-select') continue;
			
				$newItemFields[$field['name']] = $field['value'];
			}
			VarDumper::dump($newItemFields);
		}
	}
	
	public function filters(){
		return array(
			'ajaxOnly + index',
		);
	}
}