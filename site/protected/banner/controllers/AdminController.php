<?php

class AdminController extends BackendController{
    
    public $defaultAction = 'index';
    public $menu = array();
    public $layout = '//layouts/column1';
    
    public function init(){     
        parent::init();
        
                
        if(!Yii::app()->user->checkAccess('bannerManagement')){            
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        
        $favActions = $this->module->getFavoriteActions();
        
        foreach($favActions as $key=>$val){
            $this->menu[] = array('label' => '<img src="'.Yii::app()->theme->baseUrl.'/images/icons/control/32/'.$val['icon'].'.png" /><span>'.$val['title'].'</span>', 'url' => $val['url']);
        }
    }
    
    public function actions(){
        return array(
            'index' => array(
                'class' => 'application.modules.banner.controllers.admin.IndexAction'
            ),
            'create' => array(
                'class' => 'application.modules.banner.controllers.admin.CreateAction'
            ),
            'update' => array(
            	'class' => 'application.modules.banner.controllers.admin.UpdateAction'
            ),
            'delete' => array(
            	'class' => 'application.modules.banner.controllers.admin.DeleteAction'
            ),
            'position' => array(
                'class' => 'application.modules.banner.controllers.admin.PositionAction'
            ),
            'viewpos' => array(
                'class' => 'application.modules.banner.controllers.admin.PositionViewAction'
            ),
            'createpos' => array(
                'class' => 'application.modules.banner.controllers.admin.PositionCreateAction'
            ),
            'updatepos' => array(
                'class' => 'application.modules.banner.controllers.admin.PositionUpdateAction'
            ),
            'deletepos' => array(
                'class' => 'application.modules.banner.controllers.admin.PositionDeleteAction'
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
		$model=Banner::model()->findByPk($id, $criteria);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='banner-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}