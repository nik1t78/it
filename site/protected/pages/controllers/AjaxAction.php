<?php

class AjaxAction extends CAction{
    /**
     * Контроллер, обрабатывающий события вызванные ajax-запросами
     **/
    public function run(){   
		//если запрос асинхронный, то нам нужно отдать только данные
        /**
		if(Yii::app()->request->isAjaxRequest){	
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
		}**/
    }
    
	
	public function filters(){
		return array(
			'ajaxOnly + index',
		);
	}
}