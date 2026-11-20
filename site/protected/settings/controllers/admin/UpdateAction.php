<?php

class UpdateAction extends CAction{
    
    public function run(){        
        
	if(isset($_POST['Settings']))
	{
            $model = SettingsParams::model();
            $params = $model->findAll('setting_id=:setting_id', array(':setting_id' => $_POST['Settings']['id']));
                if($params === null)
                    throw new CHttpException(404, Yii::t('settings', 'Сохраняемые настройки не найдены в БД.'));
            
            $module = Settings::model()->findByPk($_POST['Settings']['id']);
            if($module === null)
                throw new CHttpException(404, Yii::t('settings', 'Настройки не соответствуют указанному модулю'));
            
            $transaction = $model->dbConnection->beginTransaction();
            try{
                //поиск и сохранение - шаги, которые можно разбить третьим запросом 
                //соответственно, мы используем транзакцию, чтобы удостовериться в целостности
                
                foreach($params as $param){
                    //ТУТ в дальнейшем возможно заменить придется, чтобы вручную ноль не присваивать.
                    $param->value = isset($_POST['SettingsParams'][$param->name]) ? $_POST['SettingsParams'][$param->name] : '';
                    //$param->value = $_POST['SettingsParams'][$param->name];
                    if(!$param->save()){
                        Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, print_r($param->getErrors(), true));
                    }
                }
                $transaction->commit();
                
                Yii::app()->user->setFlash(AFlashMessages::NOTICE_MESSAGE, Yii::t('settings', 'Настройки успешно сохранены'));
                
                $this->controller->redirect(array('/settings/admin/view/','module'=>$module->name));
            }
            catch(Exception $e){
                $transaction->rollBack();
                $this->controller->redirect(array('/backend/settings'));
            }		
	}
    }
}