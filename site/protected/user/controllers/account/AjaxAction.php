<?php

class AjaxAction extends CAction{
    /**
     * Контроллер, обрабатывающий события вызванные ajax-запросами
     **/
    public function run(){   
        //если запрос асинхронный, то нам нужно отдать только данные
        
	if(Yii::app()->request->isAjaxRequest){	
            if(isset($_POST['eventType'])){
                $eventType = $_POST['eventType'];
                
                switch($eventType){
                    case "getContragentByTin":
                        $tin = isset($_POST['tin'])?$_POST['tin']:'';
                        //$tin = (int)$tin;
                        if(empty($tin)){
                            throw new CHttpException(400, 'Для поиска требуется ИНН');
                        //}elseif(!is_int($tin)){
                        //    throw new CHttpException(400, 'ИНН должен состоять только из цифр');
                        }
                        //print_r($tin);
                        echo json_encode(Contragent::searchByTin($tin));
                        /*
                        $result = $this->getContragentByTin($tin);
                        echo $result;*/
                        break;
                                            
                    default:
                        echo "Error. Not requested action.";
                        break;
                }
            }
            Yii::app()->end();
        }else{
            //если запрос не асинхронный, 
            throw new CHttpException(400,'The requested page does not exist.');
        }
    }
    
	
    public function filters(){
        return array(
            'ajaxOnly + index',
        );
    }
    
    
    public function getContragentByTin($tin){
        $orgArr = array();
        $contragents = Contragent::model()->findAll('`tin` LIKE "%'.$tin.'%"');
        if(count($contragents) <= 0){
            $orgArr['notify'][] = 'Организация с указанным ИНН не найдена.';
        } else{
            foreach($contragents as $org){
                $orgArr['result'][$org->id]['name'] = $org->name;
                $orgArr['result'][$org->id]['tin'] = $org->tin;
            }
        }
        return json_encode($orgArr);            
    }
}