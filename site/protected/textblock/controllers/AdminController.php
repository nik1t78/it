<?php

class AdminController extends BackendController{
    
    public $defaultAction = 'index';
    public $menu = array();
    public $layout = '//layouts/column1';
    
    public function init(){     
        parent::init();
        
                
        if(!Yii::app()->user->checkAccess('textblockManagement')){            
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
                'class' => 'application.modules.textblock.controllers.admin.IndexAction'
            ),
            'create' => array(
                'class' => 'application.modules.textblock.controllers.admin.CreateAction'
            ),
            'update' => array(
            	'class' => 'application.modules.textblock.controllers.admin.UpdateAction'
            ),
            'delete' => array(
            	'class' => 'application.modules.textblock.controllers.admin.DeleteAction'
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
		$model=Textblock::model()->findByPk($id, $criteria);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='textblock-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}