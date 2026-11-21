<?php

class AdminController extends BackendController{
    public $defaultAction = 'list';
    public $menu = array();
    public $layout = '//layouts/column1';
    
    public function init(){     
        parent::init();
        
        if(!Yii::app()->user->checkAccess('userManagement')){            
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        $favActions = $this->module->getFavoriteActions();
        foreach($favActions as $key=>$val){
            $this->menu[] = array('label' => Yii::t('user', $val), 'url' => array('/user/admin/'.$key));
        }
    }
    
    public function actions(){
        return array(
            'ajax' => array(
                'class' => 'application.modules.user.controllers.admin.AjaxAction'
            ),
            'list' => array(
                'class' => 'application.modules.user.controllers.admin.ListAction'
            ),
            'view' => array(
                'class' => 'application.modules.user.controllers.admin.ViewAction'
            ),
            'create' => array(
                'class' => 'application.modules.user.controllers.admin.CreateAction'
            ),
            'update' => array(
                'class' => 'application.modules.user.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.user.controllers.admin.DeleteAction'
            ),
            'roles' => array(
                'class' => 'application.modules.user.controllers.admin.RolesAction'
            ),
            'rolecreate' => array(
                'class' => 'application.modules.user.controllers.admin.RoleCreateAction'
            ),
            'roleupdate' => array(
                'class' => 'application.modules.user.controllers.admin.RoleUpdateAction'
            ),
            'roledelete' => array(
                'class' => 'application.modules.user.controllers.admin.RoleDeleteAction'
            ),
            'userconfirm' => array(
                'class' => 'application.modules.user.controllers.admin.UserConfirmAction'
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
		$model=User::model()->findByPk($id, $criteria);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
