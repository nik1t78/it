<?php

class AdminController extends BackendController
{
    public $defaultAction = 'index';
    public $menu = array();

    public function init()
    {
        parent::init();

        if (!Yii::app()->user->checkAccess('contestManagement')) {
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа к разделу '.$this->module->getName()));
            $this->redirect(array('/backend'));
        }

        $favActions = $this->module->getFavoriteActions();
        foreach ($favActions as $key=>$val) {
            $this->menu[] = array('label' => Yii::t('contest', $val), 'url' => array('/contest/admin'.$key));
        }
    }

    public function actions()
    {
        return array(
            'index' => array(
                'class' => 'application.modules.contest.controllers.admin.IndexAction'
            ),
            'view' => array(
                'class' => 'application.modules.contest.controllers.admin.ViewAction'
            ),
            'create' => array(
                'class' => 'application.modules.contest.controllers.admin.CreateAction'
            ),
            'update' => array(
                'class' => 'application.modules.contest.controllers.admin.UpdateAction'
            ),
            'delete' => array(
                'class' => 'application.modules.contest.controllers.admin.DeleteAction'
            ),
            'member_create' => array(
                'class' => 'application.modules.contest.controllers.admin.member.CreateAction'
            ),
            'member_update' => array(
                'class' => 'application.modules.contest.controllers.admin.member.UpdateAction'
            ),
            'member_delete' => array(
                'class' => 'application.modules.contest.controllers.admin.member.DeleteAction'
            ),
            'stages' => array(
                'class' => 'application.modules.contest.controllers.admin.stage.ListAction'
            ),
            'stage_create' => array(
                'class' => 'application.modules.contest.controllers.admin.stage.CreateAction'
            ),
            'stage_delete' => array(
                'class' => 'application.modules.contest.controllers.admin.stage.DeleteAction'
            ),
            'stage_update' => array(
                'class' => 'application.modules.contest.controllers.admin.stage.UpdateAction'
            ),
			'check_voters' => array(
				'class' => 'application.modules.contest.controllers.admin.CheckVotersAction'
			)
        );
    }
}