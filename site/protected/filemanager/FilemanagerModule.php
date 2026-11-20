<?php

class FilemanagerModule extends AWebModule
{
    public $showInAdminMenu = true;
    public $moduleCode = 'files';
    public $adminMenuOrder = 30;
    public $backendLayout = '';
    public $allowedExtensions = array('jpg', 'png', 'gif', 'swf', 'flv', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'wav', 'mp3', 'mp4', 'avi', 'pdf', 'rar', 'zip');
    public $maxFileSize = 150000000;
    public $minFileSize = 1024;
    public $makePreview = true;
    public $previewParams = array(
    	'width' => 200,
    	'height' => 200,
    	'quality' => 80,
    	'saveTo' => 'adjacently', // рядом, folder - в папке
    	'suffix' => '_preview',
    );
    
    public function getVersion(){
        return '0.0.1';
    }
    
    
    /**
     * Параметры модуля, которые можно поменять через админку 
     **/
    public function getEditableParams(){
        return array(
            'adminMenuOrder' => Yii::t('system', 'Порядок следования в меню'),
            'showInAdminMenu' => Yii::t('system', 'Показывать в меню'),
            'backendLayout' => Yii::t('system', 'Шаблон модуля'),
            'allowedExtensions' => Yii::t('filemanager', 'Допустимые для загрузки расширения файлов'),
            'maxFileSize' => Yii::t('filemanager', 'Максимальный размер загружаемого файла'),
            'minFileSize' => Yii::t('filemanager', 'Минимальный размер загружаемого файла'),
            'makePreview' => Yii::t('filemanager', 'Автоматически делать превью при загрузке изображений'),
            'previewParams_width' => Yii::t('filemanager', 'Ширина превью'),
            'previewParams_height' => Yii::t('filemanager', 'Высота превью'),
            'previewParams_quality' => Yii::t('filemanager', 'Качество сжатия'),
            'previewParams_saveTo' => Yii::t('filemanager', 'Место сохранения превью (adjacently - рядом с исходником, folder - в отдельной папке)'),
            'previewParams_suffix' => Yii::t('filemanager', 'Суффикс в имени файла превью'),
        );
    }
    
    /**
     * Категория модуля, используется для сортировки модулей в админке
     **/
    public function getCategory(){
        return array('type'=>'core', 'title'=>Yii::t('filemanager', 'Файл-менеджер'));
    }
    
    /**
     * Возвращает название модуля.
     **/
    public function getName(){
        return Yii::t('filemanager', 'Файл-менеджер');
    }
    
    /**
     * Возвращает описание модуля.
     **/
    public function getDescription(){
        return Yii::t('filemanager', 'Управление файлами на сервере');
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
    
    public function getAllowedExtensions(){
        return $this->getModuleSettings('allowedExtensions');
    }
    
    /**
     * Избранные действия. Наиболее требуемые действия модуля. Выводятся в боковом меню при активизации модуля
     **/
    public function getFavoriteActions(){
        return array(
            'list' => Yii::t('filemanager', 'Список'),
            'create' => Yii::t('filemanager', 'Создать')
        );
    }
    
    
    public function modulePermission(){
        return array(
            'filemanagerManagement' => array(
                'description' => Yii::t('filemanager', 'Управление файлменеджером'),
                'operations' => array(
                    'accessFilemanager' => Yii::t('filemanager', 'Доступ к файловому менеджеру'),               
                )
            )            
        );
    }
    
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
        
        parent::init();
        // подключаем модели и компоненты модуля
		$this->setImport(array(
			'filemanager.models.*',
			'filemanager.components.*',
		));
	}
}
