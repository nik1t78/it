<?php

class OrgConfirmAction extends CAction{
    
    public function run($id){        
        if(Yii::app()->request->isPostRequest){
            $model = Contragent::model()->findByPk($id);            
            $model->confirm = Contragent::CONFIRM;
            if($model->save()){
                echo json_encode(array(
                    'status' => 'success'
                ));
                Yii::app()->end();
            }
            echo json_encode(array(
                'status' => 'error',
                'message' => 'При одобрении записи возникла ошибка.'
            ));
        }else{
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
	}
    }
}