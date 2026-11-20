<?php

class BackendModule extends AWebModule
{
    public $showInAdminMenu = false;
    public $adminMenuOrder = 1;
    public $backendLayout = '';
    public $moduleCode = 'backend';
        
    public function getShowInAdminMenu(){
        return $this->getModuleSettings('showInAdminMenu');
    }
    
    public function getAdminMenuOrder(){
        return $this->getModuleSettings('adminMenuOrder');
    }
    
    public function getBackendLayout(){
        return $this->getModuleSettings('backendLayout');
    }
    
    public function getVersion(){
        return '0.0.1';
    }
    
    public function getParamsLabels(){
        return array(
            'siteDescription' => Yii::t('artcms', 'Описание сайта'),
            'siteName' => Yii::t('artcms', 'Название сайта'),
            'siteKeyWords' => Yii::t('artcms', 'Ключевые слова сайта'),
            'backendLayout' => Yii::t('artcms', 'Layout административной части'),
        );
    }
    
    public function getEditableParams(){
        /** **/
        return array('backendLayout', 'siteName', 'siteDescription', 'siteKeyWords');
    }
    
    public function getCategory(){
        return array('type'=>'core', 'title'=>Yii::t('artcms', 'Ядро'));
    }
    /**    
    public function getAdminPageLink()
    {
        return '/yupe/backend/modulesettings/module/yupe'; // !!!!!!!!!!!!
    }
    **/
    public function getName(){
        return Yii::t('artcms', 'Основные параметры');
    }
    
    public function getDescription()
    {
        return Yii::t('artcms', 'Без этого модуля ничего не работает');
    }

    
    public function modulePermission(){
        return array(
            'backendManagement' => array(
                'description' => Yii::t('backend', 'Управление системой'),
                'operations' => array(
                    'accessBackend' => Yii::t('backend', 'Доступ к системе администрирования'),                    
                )
            )            
        );
    }
    
	
    
    public function init()
    {
	// this method is called when the module is being created
	// you may place code here to customize the module or the application
        parent::init();
            // import the module-level models and components
        $this->setImport(array(
            'backend.models.*',
            'backend.components.*',
            'backend.widgets.*',
        ));
        //print_r(Yii::app()->modules);
    }
    
    public function getModules($navigationOnly = true){
        $modules = $category = $yiiModules = $order = $modulesNavigation = array();
       /* 
        $modulesNavigation = array(
            'settings' => array(
                'items' => array(),
                'label' => Yii::t('artcms', 'Настройки'),
                'url' => '#',
                'linkOptions' => array('class' => 'sub-menu')
            ));
        */
        $modulesArr = Yii::app()->modules;
        if(count($modulesArr)){
            foreach($modulesArr as $key=>$value){
                $key = strtolower($key);
                $module = Yii::app()->getModule($key);
                
                if($module !== null){
                    if(is_a($module, 'AWebModule')){
                        //if ($module->getIsShowInAdminMenu() || $module->getEditableParams() || ($module->getIsShowInAdminMenu() == false && is_array($module->checkSelf())))
                        //{
                            $modules[$key] = $module;

                        //    $category[$key] = $module->getCategory();

                            $order[$key] = $module->getAdminMenuOrder();
                        //}

                    }else{
                        $yiiModules[$key] = $module;
                    }
                }
            }
            asort($order);
            //VarDumper::dump($order);
            
            foreach($order as $key=>$value){
                if($modules[$key]->getShowInAdminMenu() == false) continue;
                $itemClass = $modules[$key]->moduleCode?array('class'=>$modules[$key]->moduleCode):array();
                array_push($modulesNavigation, array('label'=>$modules[$key]->getName(), 'url'=>array($modules[$key]->getAdminPageLink()), 'itemOptions'=>$itemClass));                
            }
        }
        array_unshift($modulesNavigation, array('label' => Yii::t('artcms', 'Главная'), 'url' => array('/backend/default/index'), 'itemOptions'=>array('class'=>'dash')));
        /** Тестовый пункт меню содержащийподменю
         * array_push($modulesNavigation, array('label' => Yii::t('artcms', 'Модули'), 'url' => array('/modules/admin'), 'items' => array(
            array('label'=>'Модуль 1', 'url' => 'module1'),
            array('label'=>'Модуль 2', 'url' => 'module2'),
        )));
        **/
        array_push($modulesNavigation, array('label' => Yii::t('artcms', 'Отчеты'), 'url' => array('/reports/admin'), 'itemOptions'=>array('class'=>'charts')));
        /** Пункт меню, появляющийся в зависимоти от авторизации пользователя
        array_push($modulesNavigation, array('label' => Yii::t('artcms', 'Войти'), 'url' => array('/users/account/login'), 'visible' => Yii::app()->user->isGuest));
        //array_push($modulesNavigation, array('label' => Yii::t('artcms', 'Выйти ({username})',array('{username}' => Yii::app()->user->username)), 'url' => array('/users/account/logout'), 'visible' => !Yii::app()->user->isGuest));**/ 
        
        return $navigationOnly === true? $modulesNavigation : array('modules' => $modules, 'yiiModules' => $yiiModules, 'modulesNavigation' => $modulesNavigation);
    }
}
