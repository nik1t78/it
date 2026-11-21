<?php

class IBlockModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 0;
    public $backendLayout = '';
    public $moduleCode = 'tables';
    public $linkable = true; //Модуль может быть связываемым с другими модулями

    /*
     * Правила роутинга
     */
    public static function rules (){
        return array(
            'iblock/ajax/<action:\w+>' => 'iblock/ajax/<action>',
            'iblock/subscribe/<action:\w+>' => 'iblock/subscribe/<action>',
             'iblock/subscribe' => 'iblock/subscribe/index',
            //'iblock/rss/<iblock:\w+>' => 'iblock/default/rss',
            'iblock/<iblock:\w+>/<section:[\w_\/-]+>/e/<e_code:[\w_-]+>'=>'iblock/default/element',    //выводим элемент по коду
            //'iblock/<iblock:\w+>/<section:[\w_\/-]+>/type_<type:\w+>'=>'iblock/default/view',       //выводим индекс раздела с учетом типа
            'iblock/<iblock:\w+>/e/<e_code:[\w_-]+>'=>'iblock/default/element',
            //'iblock/<iblock:\w+>/p/<e_code:[\w_-]+>'=>'iblock/default/print',
            'iblock/<iblock:\w+>/<section:[\w_\/-]+>'=>'iblock/default/view',                       //выводим индекс раздела
            'iblock/<iblock:\w+>' => 'iblock/default/view',                                         //выводим индекс инфоблока - корневые разделы и элементы
            'iblock/<iblock:\w+>/<month:\d{2}\.\d{4}>' => 'iblock/default/month',        //вывод элементов по месяцам
            'iblock/<iblock:\w+>/<date:\d{2}\.\d{2}\.\d{4}>' => 'iblock/default/date',
            'iblock'=>'iblock/default/index',
        );
    }

    public function getVersion(){
        return '0.0.1';
    }

    /**
     * Значения параметров модуля, используемых при настройках модуля
     **/
    public function getParamsLabels(){
        return array(
            'adminMenuOrder' => Yii::t('iblock', 'Порядок следования в меню'),
        );
    }

    /**
     * Параметры модуля, которые можно поменять через админку
     **/
    public function getEditableParams(){
        return array(
            //'maxCaptchaLength',
            //'showCaptcha' => $this->getChoice(),
        );
    }

    /**
     * Категория модуля, используется для сортировки модулей в админке
     **/
    public function getCategory(){
        return array('type'=>'info', 'title'=>Yii::t('iblock', 'Информационные'));
    }

    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('iblock', 'Информационные блоки');
    }

    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('iblock', 'Универсальные блоки для создания собственных виджетов');
    }

    /**
     * Возвращает true если модуль должен быть указан в меню админки и false если не должен
     **/
    public function getShowInAdminMenu(){
        return $this->getModuleSettings('showInAdminMenu');
    }

    public function getAdminMenuOrder(){
        return $this->getModuleSettings('adminMenuOrder');
    }

    public function getBackendLayout(){
        return $this->getModuleSettings('backendLayout');
    }

    public function getLinkable(){
        return $this->getModuleSettings('linkable');
    }
    /**
     * Избранные действия. Наиболее требуемые действия модуля. Выводятся в боковом меню при активизации модуля
     **/
    public function getFavoriteActions(){
        return array(
            'list' => Yii::t('iblock', 'Список'),
            'create' => Yii::t('iblock', 'Создать')
        );
    }

    public function modulePermission()
    {
        return array(
            'iblockManagement' => array(
                'description' => Yii::t('iblock', 'Управление инфоблоками'),
                'operations' => array(
                    'createIBlock' => Yii::t('iblock', 'Создание инфоблока'),
                    'viewIBlock' => Yii::t('iblock', 'Просмотр списка инфоблоков'),
                    'readIBlock' => Yii::t('iblock', 'Просмотр инфоблока'),
                    'updateIBlock' => Yii::t('iblock', 'Изменение инфоблока'),
                    'deleteIBlock' => Yii::t('iblock', 'Удаление инфоблока'),
                    'publishIBlock' => Yii::t('iblock', 'Публикация элемента')
                )
            )
        );
    }

    public function search($query, $iblock_code='')
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'detail_text LIKE :keyword OR preview_text LIKE :keyword OR name LIKE :keyword';
        $criteria->params = array(
            ':keyword' => '%'.$query.'%',
        );

        if (!empty($iblock_code)) {
            $criteria->addCondition('iblock_id=(SELECT id FROM {{iblock}} WHERE code=:code)');
            $criteria->params[':code'] = $iblock_code;
        }

        $materialCount = IBlockElement::model()->count($criteria);

        $pages = new CPagination($materialCount);
        $pages->pageSize = Yii::app()->params['materialsPerPage'];
        $pages->applyLimit($criteria);

        $tmpElements = IBlockElement::model()->findAll($criteria);

        $materials = array();
        foreach ($tmpElements as $element) {
            $materials[] = array(
                'title' => $element->name,
                'anons' => $element->preview_text,
                'body' => $element->detail_text,
                'url' => $element->url
            );
        }

        return array('materials' => $materials, 'pages' => $pages, 'pageTitle' => 'Поиск в новостях');
    }

    public function init()
    {
	// this method is called when the module is being created
	// you may place code here to customize the module or the application

        parent::init();
        // подключаем модели и компоненты модуля
	$this->setImport(array(
		'iblock.models.*',
		'iblock.components.*',
	));
    }

    public function beforeControllerAction($controller, $action) {
        if(parent::beforeControllerAction($controller, $action))
            return true;
        else
            return false;
    }

    public function loadSections()
    {
        $sections = IBlock::getList();
        return $sections;
    }

    public function commentTarget($type)
    {
        $result = null;
        switch ($type) {
            case 'element':
                $result = new IBlockElement();
                break;
            case 'section':
                $result = new IBlockSection();
                break;
        }

        return $result;
    }
}
