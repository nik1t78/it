<?php

/**
 * Базовый контроллер фронтэнда
 */
class FrontendController extends CController{
    public $menu = array();
    public $breadcrumbs = array();    
    public $sideMenu = array();
    public $description;
    public $keywords;
    
    private $_pageTitle;
    
    /**
     * Устанавливаем заголовок страницы.
     * Имя сайта | Заголовок
     */
    public function setPageTitle($title)
    {        
        $this->_pageTitle = $title . ' | ' . $this->getPageTitle();
    }
    
    public function getPageTitle(){
        if($this->_pageTitle!==null)
            return $this->_pageTitle;
	else
	{
            return $this->_pageTitle=Yii::app()->name;
	}
    }

    /**
     * Инициализация
     */
    public function init()
    {
        //$this->pageTitle = Yii::app()->getModule('yupe')->siteName; //Устанавливаем заголовок страницы, считанный из параметров основного модуля
        //$this->description = Yii::app()->getModule('yupe')->siteDescription; //описание
        //$this->keywords = Yii::app()->getModule('yupe')->siteKeyWords; // и ключевые слова для SEO

        $baseUrl = Yii::app()->baseUrl;

        //Yii::app()->clientScript->registerScript('yupe_base_url', "var baseUrl = '$baseUrl';", CClientScript::POS_HEAD); // устанавливает в JS полный путь 
    }
    
    public function behaviors() {
        return array_merge(parent::behaviors(), array(
            'DLiveLayoutBehavior'=>array('class'=>'DLiveLayoutBehavior'),
            'InlineWidgetsBehavior'=>array(
                'class'=>'DInlineWidgetsBehavior',
                'location'=>'application.components.widgets',
                'startBlock'=>'{{w:',
                'endBlock'=>'}}',
                'widgets'=>Yii::app()->params['runtimeWidgets'],
            )
        ));
    }
    
    public function beforeRender($view) {
        $this->initLayout(); 
        return parent::beforeRender($view);
    }
    
    
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        $this->layout = '//layouts/column2';
        if($error=Yii::app()->errorHandler->error)
        {
            if(Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }
}