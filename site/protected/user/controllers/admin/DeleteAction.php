<?php

class DeleteAction extends CAction{
    
    public function run($id){
        if(Yii::app()->request->isPostRequest)
	{
            
            $user = User::model()->findByPk($id);
            
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
            
        }
        else{
            // we only allow deletion via POST request
            $this->controller->loadModel($id)->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                $this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/backend/user/'));
        }
			
    }
}