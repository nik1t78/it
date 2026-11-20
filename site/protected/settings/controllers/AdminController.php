<?php

class AdminController extends BackendController{
    
    public $defaultAction = 'index';
    public $menu = array();
    
    public function init(){     
        parent::init();
        
                
        if(!Yii::app()->user->checkAccess('settingsManagement')){            
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        //$favActions = $this->module->getFavoriteActions();
        $modules = Yii::app()->getModule('backend')->getModules(false);
       
        foreach($modules['modules'] as $key=>$val){
            if($val->getShowInAdminMenu() === false) continue;
                array_push($this->menu, array('label'=>$val->getName(), 'url'=>array('/settings/admin/'.$val->getId())));                
        }
        //foreach($favActions as $key=>$val){
        //    $this->menu[] = array('label' => Yii::t('settings', $val), 'url' => array('/settings/admin/'.$key));
        //}
    }
    
    public function actions(){
        return array(
            'index' => array(
                'class' => 'application.modules.settings.controllers.admin.IndexAction'
            ),
            'list' => array(
                'class' => 'application.modules.settings.controllers.admin.ListAction'
            ),
            'view' => array(
                'class' => 'application.modules.settings.controllers.admin.ViewAction'
            ),
            'update' => array(
                'class' => 'application.modules.settings.controllers.admin.UpdateAction'
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
		$model=Settings::model()->findByPk($id, $criteria);
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