<?php

class ContestModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $adminMenuOrder = 0;
    public $backendLayout = '';
    public $moduleCode = 'tables';

    public static function rules()
    {
        return array(
            //'contest/<action:\w+>' => 'contest/<action>',
            //'contest/<controller:\w+>/<action:\w+>' => 'contest/<controller>/<action>',
            'contest/<id:\w+>/<nid:\d+>' => 'contest/default/view',
            'contest/<id:\w+>' => 'contest/default/view',
            'contest' => 'contest/default/index'
        );
    }

    public function getVersion()
    {
        return '0.0.1';
    }

    /*
     * Значения параметров модуля, используемых при настройках модуля
     */
    public function getParamsLabels()
    {
        return array(
            'adminMenuOrder' => Yii::t('system', 'Порядок следования в меню.'),
        );
    }

    /*
     * Параметры модуля, которые можно поменять через админку
     */
    public function getEditableParams()
    {
        return array(
            //'maxCaptchaLength',
            //'showCaptcha' => $this->getChoice(),
        );
    }

    /*
     * Категория модуля, используется для сортировки модулей в админке
     */
    public function getCategory()
    {
        return array(
            'type' => 'info',
            'title' => Yii::t('system', 'Информационные')
        );
    }

    /*
     * Возвращает название модуля.
     */
    public function getName()
    {
        return Yii::t('contest', 'Конкурсы');
    }

    /*
     * Возвращает описание модуля
     */
    public function getDescription()
    {
        return Yii::t('contest', 'Конкурсы');
    }

    /*
     * Возвращает true если модуль должен быть указан в меню админки и false если не должен
     */
    public function getShowInAdminMenu()
    {
        return $this->getModuleSettings('showInAdminMenu');
    }

    public function getAdminMenuOrder()
    {
        return $this->getModuleSettings('adminMenuOrder');
    }

    public function getBackendLayout()
    {
        return $this->getModuleSettings('backendLayout');
    }

    /*
     * Избранные действия. Наиболее требуемые действия модуля. Выводятся в боковом меню при активизации модуля
     */
    public function getFavoriteActions()
    {
        return array(
            //'list' => Yii::t('contest', 'Список'),
            //'create' => Yii::t('contest', 'Создать')
        );
    }

    public function modulePermission()
    {
        return array(
            'contestManagement' => array(
                'description' => Yii::t('contest', 'Управление конкурсами'),
                'operations' => array(
                    'createContest' => Yii::t('contest', 'Создание конкурса'),
                    'viewContest' => Yii::t('contest', 'Просмотр списка конкурсов'),
                    'readContest' => Yii::t('contest', 'Просмотр конкурса'),
                    'updateContest' => Yii::t('contest', 'Изменение конкурса'),
                    'deleteContest' => Yii::t('contest', 'Удаление конкурса'),
                )
            )
        );
    }

    public function init()
    {
        parent::init();

        $this->setImport(array(
            'contest.models.*',
            'contest.components.*',
        ));
    }

    public function beforeControllerAction($controller, $action)
    {
        if (parent::beforeControllerAction($controller, $action))
            return true;
        else
            return false;
    }
}