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
                'class' => 'application.modules.contest.controllers.default.IndexAction'
            ),
            'view' => array(
                'class' => 'application.modules.contest.controllers.default.ViewAction'
            ),
            'request' => array(
                'class' => 'application.modules.contest.controllers.default.RequestAction'
            ),
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            'vote' => array(
                'class' => 'application.modules.contest.controllers.default.VoteAction'
            )
        );
    }
}