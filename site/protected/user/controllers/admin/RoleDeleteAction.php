<?php

class RoleDeleteAction extends CAction{
    
    public function run($name){
        if(Yii::app()->request->isPostRequest)
	{
                        
            $auth = Yii::app()->authManager;
            $currRole = $auth->getAuthItem($name);
            $auth->removeAuthItem($name);
            /*
            $user = User::model()->findByPk($id);
            
            if(!$user->delete()){
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'При удалении записи возникла ошибка.'
                ));
                Yii::app()->end();
            }*/
            echo json_encode(array(
                'status' => 'success'
            ));
            
        }
			
    }
}