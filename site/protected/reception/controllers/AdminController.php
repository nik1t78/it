<?php

class AdminController extends BackendController
{
    public $defaultAction = 'index';
    public $menu = array();
    
    public function init()
    {
        parent::init();
        
        if (!Yii::app()->user->checkAccess('receptionManagement')) {
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа к разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        $favActions = $this->module->getFavoriteActions();
        foreach ($favActions as $key=>$val) {
            $this->menu[] = array('label' => Yii::t('reception', $val), 'url' => array('/reception/admin/'.$key));
        }
    }
    
    public function actions()
    {
        return array(
            'index' => array(
                'class' => 'application.modules.reception.controllers.admin.IndexAction',
            ), 
            'update' => array(                
                'class' => 'application.modules.reception.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.reception.controllers.admin.DeleteAction'
            ),
            'export' => array(
                'class' => 'application.modules.reception.controllers.admin.ExportAction'
            )
        );
    }
    
    public function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'reception-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}