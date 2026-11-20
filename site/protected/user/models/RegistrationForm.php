<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class RegistrationForm extends CFormModel
{
	public $login;
        public $full_name;
        public $email;
	public $password;
        public $address;
        public $region;
        public $phoneNum;
        public $organization;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('login, full_name, email, password, address, region, phoneNum', 'required'),
                        array('organization', 'length', 'max'=>14),
			// password needs to be authenticated
			//array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'login' => Yii::t('user', 'Логин'),
			'full_name' => Yii::t('user', 'Полное имя'),
			'email' => Yii::t('user', 'E-mail'),
			'password' => Yii::t('user', 'Пароль'),
                        'address' => Yii::t('user', 'Адрес'),
                        'region' => Yii::t('user', 'Регион (область)'),
                        'phoneNum' => Yii::t('user', 'Телефон'),
                        'organization' => Yii::t('user', 'Организация'),
		);
	}
        
}
