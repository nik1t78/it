<?php

class SpecialController extends FrontendController
{
    public $menu = array();
    public $bodyPageTitle = '';
    //public $layout = '//layouts/special/column2';

    public function actions(){
        return array(
            'index' => array(
        	'class' => 'application.modules.pages.controllers.special.IndexAction'
            ),
            'ajax' => array(
        	'class' => 'application.modules.pages.controllers.special.AjaxAction'
            ),
            'view' => array(
                'class' => 'application.modules.pages.controllers.special.ViewAction'
            ),
            'contact' => array(
                'class' => 'application.modules.pages.controllers.special.ContactAction'
            ),
            'captcha'=>array(
		'class'=>'CCaptchaAction',
		'backColor'=>0xFFFFFF,
            ),
        );
    }


}
