<?php

class UserModule extends AWebModule
{
    public $adminMenuOrder = 0;
    public $adminReturnUrl = '/backend/';
    public static $logCategory = 'application.modules.user';
    public $showInAdminMenu = true;
    public $loginReturnUrl = '/';
    public $minPasswordLength = 6;
    public $maxPasswordLength = 10;
    public $moduleCode = 'ui';
    public $bindToContragent = false;
    
    public function getVersion(){
        return '0.0.1';
    }
    
    public function getParamsLabels(){
        return array(
            'adminMenuOrder' => Yii::t('user', 'Порядок следования в меню'),
            'accountActivationSuccess' => Yii::t('user', 'Страница после активации аккаунта'),
            'accountActivationFailure' => Yii::t('user', 'Страница неудачной активации аккаунта'),
            'loginSuccess' => Yii::t('user', 'Страница после авторизации'),
            'logoutSuccess' => Yii::t('user', 'Страница после выхода с сайта'),
            'notifyEmailFrom' => Yii::t('user', 'Email от имени которого отправлять сообщение'),
            'autoRecoveryPassword' => Yii::t('user', 'Автоматическое восстановление пароля'),
            'minPasswordLength' => Yii::t('user', 'Минимальная длина пароля'),
            'maxPasswordLength' => Yii::t('user', 'Максимальная длина пароля'),
            'emailAccountVerification' => Yii::t('user', 'Подтверждать аккаунт по Email'),
            'showCaptcha' => Yii::t('user', 'Показывать капчу при регистрации'),
            'minCaptchaLength' => Yii::t('user', 'Минимальная длина капчи'),
            'maxCaptchaLength' => Yii::t('user', 'Максимальная длина капчи'),
            'documentRoot' => Yii::t('user', 'Корень сервера'),
            'avatarsDir' => Yii::t('user', 'Каталог для загрузки аватарок'),
            'avatarMaxSize' => Yii::t('user', 'Максимальный размер аватарки'),
            'defaultAvatar' => Yii::t('user', 'Пустой аватар'),
            'invalidIpAction' => Yii::t('user', 'Страница для заблокированных IP'),
            'invalidEmailAction' => Yii::t('user', 'Страница для заблокированных Email'),
            'loginAdminSuccess'  => Yii::t('user','Страница после авторизации админстратора'),
            'bindToContragent' => Yii::t('user', 'Привязывать пользователей к контрагентам'),
        );
    }
    
    public function getEditableParams(){
        return array(
            'avatarMaxSize',
            'defaultAvatar',
            'avatarsDir',
            'minCaptchaLength',
            'maxCaptchaLength',
            'showCaptcha' => $this->getChoice(),
            'emailAccountVerification' => $this->getChoice(),
            'minPasswordLength',
            'maxPasswordLength',
            'autoRecoveryPassword' => $this->getChoice(),
            'notifyEmailFrom',
            'logoutSuccess',
            'loginSuccess',
            'adminMenuOrder',
            'accountActivationSuccess',
            'accountActivationFailure',
            'loginAdminSuccess',
            'bindToContragent'
        );
    }
    
    public function getCategory(){
        return array('type'=>'user', 'title'=>Yii::t('user', 'Пользователи'));
    }
    
    public function getName(){
        return Yii::t('user', 'Пользователи');
    }
    
    public function getDescription(){
        return Yii::t('user', 'Модуль для управления пользователями, регистрацией и авторизацией');
    }
            
    
    public function getShowInAdminMenu(){
        return $this->getModuleSettings('showInAdminMenu');
    }
    
    public function getAdminMenuOrder(){
        return $this->getModuleSettings('adminMenuOrder');
    }
    
    public function getBackendLayout(){
        return $this->getModuleSettings('backendLayout');
    }
    
    public function getFavoriteActions(){
        return array(
            'list' => Yii::t('user', 'Пользователи'),
            'roles' => Yii::t('user', 'Роли')
        );
    }
    
    public function modulePermission(){
        return array(
            'userManagement' => array(
                'description' => Yii::t('user', 'Управление пользователями'),
                'operations' => array(
                    'createUser' => Yii::t('user', 'Создание пользователя'),
                    'viewUsers' => Yii::t('user', 'Просмотр списка пользователей'),
                    'readUser' => Yii::t('user', 'Просмотр данных пользователя'),
                    'updateUser' => Yii::t('user', 'Изменение данных пользователя'),
                    'deleteUser' => Yii::t('user', 'Удаление пользователя'),
                    'changeRole' => Yii::t('user', 'Изменение прав доступа пользователя'),
                    'updateOwnData' => array(
                        'description' => Yii::t('user', 'Изменение своих данных'),
                        'bizRule' => 'return Yii::app()->user->id==$params["user"]->id;',
                    ),
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
			'user.models.*',
			'user.components.*',
		));
	}
}
