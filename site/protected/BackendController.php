<?php

/**
 * Базовый контроллер админки
 */
class BackendController extends CController{
    public $defaultAction = 'list';
    public $layout = '//layouts/backendIndex'; 
    public $menu=array();
    public $theme = 'backend';
    public $bodyBlockTitle = '';
    public $breadcrumbs=array();
    
    
    public function init(){     
        if(!Yii::app()->user->checkAccess('backendManagement')){/** доступ к админке для данной роли запрещен, если пользователь вообще не авторизован - переслать его на страницу входа, если авторизован, но не имеет прав доступа - вывести сообщение об ошибке **/
            if(Yii::app()->user->isGuest){
                Yii::app()->user->returnUrl = Yii::app()->request->url;
                $this->redirect(array(Yii::app()->user->loginUrl));
            }else
                throw new CHttpException(403, Yii::t('system', 'Ваших прав недостаточно для доступа к этому разделу'));
        }
        
               // VarDumper::dump(Yii::app()->user);
        
        Yii::app()->theme = $this->theme;
           
    }
}