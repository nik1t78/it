<?php

class AdminController extends BackendController{
    
    public $defaultAction = 'index';
    public $menu = array();
    
    public function init(){     
        parent::init();
        
        if(!Yii::app()->user->checkAccess('fotoManagement')){            
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        $favActions = $this->module->getFavoriteActions();
        foreach($favActions as $key=>$val){
            $this->menu[] = array('label' => Yii::t('foto', $val), 'url' => array('/foto/admin/'.$key));
        }
    }
    
    public function actions(){
        return array(
            'index' => array(
                'class' => 'application.modules.fotogallery.controllers.admin.IndexAction'
            ),
            'list' => array(
                'class' => 'application.modules.fotogallery.controllers.admin.ListAction'
            ),
            'view' => array(
                'class' => 'application.modules.fotogallery.controllers.admin.ViewAction'
            ),
            'create' => array(
                'class' => 'application.modules.fotogallery.controllers.admin.CreateAction'
            ),
            'update' => array(
                'class' => 'application.modules.fotogallery.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.fotogallery.controllers.admin.DeleteAction'
            )
        );
    }
    
    
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	public function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='foto-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
