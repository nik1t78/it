<?php

class ModulesOnDashboard extends CWidget{
    public $dashboard = array();
    
    public function run(){        
        $modules = Yii::app()->getModule('backend')->getModules(false);
        foreach($modules['modules'] as $key=>$val){
            $module = Yii::app()->getModule($key);
            if($module->getShowInAdminMenu()){
                $modCategory = $module->getCategory();
                $this->dashboard[$modCategory['type']] = array('title' => $modCategory['title']);
                $this->dashboard[$modCategory['type']]['items'][] = array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon29.png',
                        'name' => $module->getName(),
                        'url' => $module->getAdminPageLink()
                    );
            }
        }
        $this->dashboard = array_merge($this->generateStaticModules(),$this->dashboard);
        $this->render('generateDashboard');
    }
    
    private function generateStaticModules(){
        return array(
            'structure' => array(
                'title' => 'Структура',
                'items' => array(
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon4.png',
                        'name' => 'Страницы и разделы',
                        'url' => '/pages/admin/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon9.png',
                        'name' => 'Файл-менеджер',
                        'url' => '/filemanager/admin/'
                    )
                )
            ),
            'info' => array(
                'title' => 'Информационные модули',
                'items' => array(
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon5.png',
                        'name' => 'Новости',
                        'url' => '/news/admin/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon13.png',
                        'name' => 'Статьи',
                        'url' => '/stories/admin/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon6.png',
                        'name' => 'Баннеры',
                        'url' => '/banners/admin/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon12.png',
                        'name' => 'Фотогалереи',
                        'url' => '/fotoshow/admin/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon18.png',
                        'name' => 'Опросы',
                        'url' => '/polls/admin/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon6.png',
                        'name' => 'Портфолио',
                        'url' => '/portfolio/admin/'
                    )
                )
            ),
            'shop' => array(
                'title' => 'Магазин',
                'items' => array(
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon7.png',
                        'name' => 'Товары',
                        'url' => '/shop/admin/items/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon8.png',
                        'name' => 'Разделы',
                        'url' => '/shop/admin/categories/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon19.png',
                        'name' => 'Фильтры',
                        'url' => '/shop/admin/filters/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon20.png',
                        'name' => 'Заказы',
                        'url' => '/shop/admin/orders/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon29.png',
                        'name' => 'Клиенты',
                        'url' => '/shop/admin/customers/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon30.png',
                        'name' => 'Партнеры',
                        'url' => '/shop/admin/partners/'
                    )
                )
            ),
            'reports' => array(
                'title' => 'Отчеты',
                'items' => array(
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon22.png',
                        'name' => 'Статистика',
                        'url' => '/statistic/admin/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon23.png',
                        'name' => 'Логи',
                        'url' => '/logs/admin/'
                    )
                )
            ),
            'settings' => array(
                'title' => 'Настройки',
                'items' => array(
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon24.png',
                        'name' => 'Основные',
                        'url' => '/settings/admin/index/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon27.png',
                        'name' => 'Обслуживание БД',
                        'url' => '/settings/admin/db/'
                    ),
                    array(
                        'icon' => Yii::app()->theme->baseUrl.'/images/icon11.png',
                        'name' => 'Оформление (шаблоны)',
                        'url' => '/settings/admin/templates/'
                    )
                )
            )
        );
    }
}