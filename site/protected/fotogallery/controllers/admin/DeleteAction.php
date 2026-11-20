<?php

class DeleteAction extends CAction{
    
    public function run($id, $type){
                
        if(Yii::app()->request->isPostRequest)
	{
            // we only allow deletion via POST request
            //$this->controller->loadModel($id)->delete();
            
            if($type == 'gallery'){
                //удаляем инфоблок
                $model = FotoGallery::model()->findByPk($id);
                   
            }elseif($type == 'section'){
                //удаляем раздел
                $model = FotoSection::model()->findByPk($id);
                  
            }elseif($type == 'foto'){
                //удаляем элемент
                $model = Foto::model()->findByPk($id);
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
            throw new CHttpException(400, Yii::t('artcms', 'Неверный запрос. Пожалуйста, повторите попытку позже.'));
	}
			
    }
}
