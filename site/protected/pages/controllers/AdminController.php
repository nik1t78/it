<?php

class AdminController extends BackendController{
    
    public $defaultAction = 'index';
    public $menu = array();
    public $layout = '//layouts/column1';
    
    public function init(){     
        parent::init();
        
                
        if(!Yii::app()->user->checkAccess('pagesManagement')){            
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        $favActions = $this->module->getFavoriteActions();
        foreach($favActions as $key=>$val){
            $this->menu[] = array('label' => Yii::t('pages', $val), 'url' => array('/pages/admin/'.$key), 'linkOptions' => array('rel' => $key.'Pages'));
        }
    }
    
    public function actions(){
        return array(
            'ajax' => array(
        	'class' => 'application.modules.pages.controllers.admin.AjaxAction'
            ),
            'index' => array(
                'class' => 'application.modules.pages.controllers.admin.IndexAction'
            ),
            'view' => array(
                'class' => 'application.modules.pages.controllers.admin.ViewAction'
            ),            
            'create' => array(
                'class' => 'application.modules.pages.controllers.admin.CreateAction'
            ),
            'update' => array(
                'class' => 'application.modules.pages.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.pages.controllers.admin.DeleteAction'
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
		$model=Pages::model()->findByPk($id, $criteria);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='pages-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    
}