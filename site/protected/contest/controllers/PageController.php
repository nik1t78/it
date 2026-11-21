<?php

class DefaultController extends FrontendController
{
    public $template = 'default';
    public $bodyPageTitle = '';
    public $pageSize = 10;

    public function actions()
    {
        return array(
            'index' => array(
                'class' => 'application.modules.contest.controllers.page.IndexAction'
            ),
            'view' => array(
                'class' => 'application.modules.contest.controllers.page.ViewAction'
            ),
        );
    }
}