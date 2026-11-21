<?php

Yii::import('iblock.models.*');
class DeleteAction extends CAction{
    
    public function run($id, $type){
                
        if(Yii::app()->request->isPostRequest)
	{
            // we only allow deletion via POST request
            //$this->controller->loadModel($id)->delete();
            
            if($type == 'iblock'){
                //удаляем инфоблок
                $model = IBlock::model()->findByPk($id);
                   
            }elseif($type == 'section'){
                //удаляем раздел
                $model = IBlockSection::model()->findByPk($id);
                  
            }elseif($type == 'element'){
                //удаляем элемент
                $model = IBlockElement::model()->findByPk($id);
            }else{
                //ничего не удаляем
            }
              
            
            if(!$model->delete()){
                /*echo json_encode(array(
                    'status' => 'error',
                    'message' => 'При удалении записи возникла ошибка.'
                ));*/
                Yii::app()->end();
            }else{
                echo json_encode(array(
                    'status' => 'success'
                ));
                Yii::app()->end();
            }/**/
            
            
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
		$this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/backend/iblock/'));/****/
	}
	else{
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
	}
			
    }
}
