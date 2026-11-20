<?php

class AdminController extends BackendController
{
    public $defaultAction = 'index';
    public $menu = array();
    
    public function init()
    {
        parent::init();
        
        if (!Yii::app()->user->checkAccess('pollsManagement')) {
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа к разделу '.$this->module->getName()));
            $this->redirect(array('/backend/'));
        }
        
        $favActions = $this->module->getFavoriteActions();
        foreach ($favActions as $key=>$val) {
            $this->menu[] = array('label' => Yii::t('polls', $val), 'url' => array('/polls/admin/'.$key));
        }
    }
    
    public function actions()
    {
        return array(
            'index' => array(
                'class' => 'application.modules.polls.controllers.admin.IndexAction'
            ),
            'create' => array(
                'class' => 'application.modules.polls.controllers.admin.CreateAction'
            ),
            'view' => array(
                'class' => 'application.modules.polls.controllers.admin.ViewAction'
            ),
            'update' => array(
                'class' => 'application.modules.polls.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.polls.controllers.admin.DeleteAction'
            )
        );
    }
    
    public function performAjaxValidation($model)
    {
        if (!isset($_POST['ajax']) && $_POST['ajax'] === 'polls-form') {
            echo CActionForm::validate($model);
            Yii::app()->end;
        }
    }
}
