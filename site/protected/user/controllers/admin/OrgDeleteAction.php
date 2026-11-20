<?php

class OrgDeleteAction extends CAction{
    
    public function run($id){
        if(Yii::app()->request->isPostRequest)
	{
            // we only allow deletion via POST request
            //$this->controller->loadModel($id)->delete();
            $user = Contragent::model()->findByPk($id);
            //echo $_GET['ajax'];
            if(!$user->delete()){
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'При удалении записи возникла ошибка.'
                ));
                Yii::app()->end();
            }
            echo json_encode(array(
                'status' => 'success'
            ));
            
            
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            //if(!isset($_GET['ajax']))
		//$this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/backend/user/'));
	}
	else{
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
	}
			
    }
}