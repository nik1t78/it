<?php

class AdminController extends BackendController{
    
    public $layout = '//layouts/column1';
    public $defaultAction = 'index';
    public $menu = array();
    
    public function init(){     
        parent::init();
        
        
        Yii::app()->theme = $this->theme;
                
        if(!Yii::app()->user->checkAccess('menuManagement')){            
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        $favActions = $this->module->getFavoriteActions();
        foreach($favActions as $key=>$val){
            $this->menu[] = array('label' => Yii::t('menu', $val), 'url' => array('/menu/admin/'.$key));
        }
    }
    
    public function actions(){
        return array(
            'index' => array(
                'class' => 'application.modules.menu.controllers.admin.IndexAction'
            ),
            'view' => array(
                'class' => 'application.modules.menu.controllers.admin.ViewAction'
            ),
            'create' => array(
                'class' => 'application.modules.menu.controllers.admin.CreateAction'
            ),
            'update' => array(
                'class' => 'application.modules.menu.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.menu.controllers.admin.DeleteAction'
            )
        );
    }
    
    
    /**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id, $criteria='')
	{
		$model=Menu::model()->findByPk($id, $criteria);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}