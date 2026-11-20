<?php

class SettingsController extends BackendController
{    
    //public $layout = '//layouts/backendIndex';
    public $defaultAction = 'list';
    public $menu = array();
      
    public function init(){     
        if(!Yii::app()->user->checkAccess('backendAccess')){/** доступ к админке для данной роли запрещен, если пользователь вообще не авторизован - переслать его на страницу входа, если авторизован, но не имеет прав доступа - вывести сообщение об ошибке **/
            if(Yii::app()->user->isGuest)
                $this->redirect(array(Yii::app()->user->loginUrl));
            else
                throw new CHttpException(403, Yii::t('users', 'Вы имеете недостаточно прав для доступа к этому разделу'));
        }
        Yii::app()->theme = $this->theme;
        
        if(!Yii::app()->user->checkAccess('settingsAdminAccess'))
            $this->redirect(array('/backend/'));
        
        $favActions = $this->module->getFavoriteActions();
        foreach($favActions as $key=>$val){
            $this->menu[] = array('label' => Yii::t('settings', $val), 'url' => array('/settings/admin/'.$key));
        }
    }
    
    public function actions(){
        return array(
            'list' => array(
                'class' => 'application.modules.backend.controllers.settings.ListAction'
            ),
            'view' => array(
                'class' => 'application.modules.backend.controllers.settings.ViewAction'
            ),
            'update' => array(
                'class' => 'application.modules.backend.controllers.settings.UpdateAction'
            ),
        );
    }
    
    
    /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $criteria='')
	{
		$model=Users::model()->findByPk($id, $criteria); /////////////////////////////////// ИЗМЕНИТЬ МОДЕЛЬ!!!!!!!!!!!!!!!!!
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
    
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	public function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='settings-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}