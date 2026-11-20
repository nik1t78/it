<?php

class DeleteAction extends CAction{
    
    public function run($id){
        if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			//$this->controller->loadModel($id)->delete();
            $user = Users::model()->findByPk($id);
            echo $_GET['ajax'];
            if(!$user->delete()){
                echo Yii::t('artcms', 'Ошибка при удалении записи');//Вывод сообщения на экран. сейчас в POST пишет
                Yii::app()->end();
            }
            
            
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/backend/users/'));
		}
		else{
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
		}
			
    }
}