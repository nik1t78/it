<?php

class User extends CActiveRecord{
    
    const ROLE_USER = 'user';
    const ROLE_MODER = 'moderator';
    const ROLE_ADMIN = 'administrator';
    const ROLE_BANNED = 'banned';
    const ROLE_MANAGER = 'manager';
    const ROLE_BUYER = 'buyer';
    
    public $prevRole = null;
    public $password2;
    public $organization_id;
    public $organization;
    
    public static function getRoles(){
        $auth = Yii::app()->authManager;
        $roles = $auth->getRoles();
        
        $result = array();
        foreach($roles as $role=>$val){
            $result[$role] = !empty($val->description) ? $val->description : $val->name;
        }
        return $result;
        /*
        return array(
            self::ROLE_USER => 'Покупатель',
            self::ROLE_MODER => 'Модератор',
            self::ROLE_ADMIN => 'Администратор',
            self::ROLE_BANNED => 'Заблокирован',
            self::ROLE_MANAGER => 'Менеджер',
        );*/
    }
    
    public static function getRole($userId){
        $auth = Yii::app()->authManager;
        $roles = $auth->getRoles($userId);
        $result = '';
        foreach($roles as $role=>$val){
            $result = !empty($val->description) ? $val->description : $val->name;
        }
        return $result;
        /*
        $roles = self::getRoles();
        if(array_key_exists($roleId, $roles)){
            return $roles[$roleId];
        }else{
            return Yii::t('user', 'Запрошенная роль не найдена');
        }*/
    }
    
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    
    public function tableName(){
        return '{{user}}';
    }
    
    public function rules(){
        return array(
            array('password', 'required', 'on' =>'create, login'),
            array('login, full_name', 'required'),
            array('password2', 'compare', 'compareAttribute'=>'password', 'on'=>'settings, registration'), /** при регистрации password должен быть равен password2 **/
            array('role, region, address, phoneNum, cookie_key', 'safe'),
            //array('name', 'required', 'on' => 'update'),
            //array('password', 'authenticate'), /** не понятно где эта функция находится... **/
            array('email', 'email'),
            array('email, password', 'required', 'on'=>'registration'),
            array('login, email', 'unique', 'on'=>'registration'),
            //array('author_id, template_id, module_id, category_id, menuOrder, mapOrder', 'numerical', 'integerOnly'=>true),
            array('login, full_name', 'length', 'max'=>100),
            array('email', 'length', 'max'=>100),
            array('password, password2', 'length', 'min'=>3, 'max'=>12),
            //array('anons, body, create_time, update_time, menuEnable, mapEnable', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('login, email, full_name', 'safe', 'on'=>'search'),
        );
    }
    /**
	* @return array relational rules.
	*/
    public function relations()
    {
	// NOTE: you may need to adjust the relation name and the related
	// class name for the relations automatically generated below.
	return array(
            
	);
    }
    
    public function attributeLabels()
    {
	return array(
            'id' => Yii::t('user', 'ID'),
            'login' => Yii::t('user', 'Логин'),
            'full_name' => Yii::t('user', 'Полное имя'),
            'email' => Yii::t('user', 'E-mail'),
            'password' => Yii::t('user', 'Пароль'),
            'password2' => Yii::t('user', 'Подтверждение пароля'),
            'role' => Yii::t('user', 'Права пользователя'),
            'address' => Yii::t('user', 'Адрес'),
            'region' => Yii::t('user', 'Регион (область)'),
            'phoneNum' => Yii::t('user', 'Телефон'),
            'organization' => Yii::t('user', 'Организация'),
            'organization_id' => Yii::t('user', 'Организация')
	);
    }
    
    /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
	// should not be searched.

	$criteria=new CDbCriteria;

	$criteria->compare('id',$this->id);
	$criteria->compare('login',$this->name,true);
	$criteria->compare('full_name',$this->full_name,true);
	$criteria->compare('email',$this->email,true);

	return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
	));
    }
    
    public function validatePassword($password){
        return $this->hashPassword($password, $this->salt) === $this->password;
    }
        
    public function hashPassword($password, $salt){
        return md5($salt.$password);
    }
    
    public function generateSalt(){
        return md5(uniqid('',true));
    }
    
    /**
    public function scopes()
    {
        return array(  фильтрация пользователей по их статусу и уровню доступа
            'active' => array(
                'condition' => 'status = :status',
                'params'    => array(':status' => self::STATUS_ACTIVE)
             ),
            'blocked' => array(
                'condition' => 'status = :status',
                'params'    => array(':status' => self::STATUS_BLOCK)
            ),    
            'notActivated' => array(
                'condition' => 'status = :status',
                'params'    => array(':status' => self::STATUS_NOT_ACTIVE)
            ),                    
            'admin' => array(
                'condition' => 'access_level = :access_level',
                'params'    => array(':access_level' => self::ACCESS_LEVEL_ADMIN)
             ),
            'user' => array(
                'condition' => 'access_level = :access_level',
                'params'    => array(':access_level' => self::ACCESS_LEVEL_USER)
            ),
        );
    }**/
    
    public function beforeSave(){
        parent::beforeSave();
        if($this->isNewRecord){
            $salt = $this->salt = $this->generateSalt(); 
            /*TODO: Назначение роли по-умолчанию*/
            //if(!Yii::app()->user->checkAccess('changeRole')){
                if($this->role == null){
                    $this->role = User::ROLE_USER; //При регистрации через форму в фронтэнде поле "Права пользователя" не указывается. по умолчанию пользователь заблокирован, разблокируется только после одобрения админа.
                }
            //}
        }else{ //Если запись редактируется
            if($this->password == null) {
                unset($this->password); //Если поле Пароль не заполнено, не сохраняем его в БД
                return true;
            }
            $salt = $this->salt;
        }
        
        $this->password = $this->hashPassword($this->password, $salt);
        return true;
        /**
        $this->password = md5($this->password);
        //Если пользователь не имеет права изменять роль, то устанавливаем роль по-умолчанию (user)
        if(!Yii::app()->user->checkAccess('changeRole')){
            if($this->isNewRecord){
                $this->role = User::ROLE_USER;
            }
        }
        return true;**/
    }
    
    public function afterSave(){
        parent::afterSave();
        
        /**
         * Привязываем пользователя к организации
         */
        if(Yii::app()->getModule('user')->bindToContragent){
            $contragent = Contragent::model()->findByPk($this->organization_id);
            if($contragent == null) 
                throw new CDbException(Yii::t('contragent', 'Организация не найдена')); //Если контрагент с указанным id в БД не существует - возвращаем ошибку
                
            if($this->isNewRecord){
                $model = new ContragentUser;
            }else{
                $model = ContragentUser::model()->find('uid=:user_id', array(':user_id' => $this->id));
                if($model == null) $model = new ContragentUser;
            }
            $model->cid = $contragent->id;
            $model->uid = $this->id;
            $model->save(false);
        }
        
        if(Yii::app()->user->checkAccess('changeRole')){ //Если у пользователя есть право на смену роли - меняем.
            //связываем нового пользователя с ролью
            $auth = Yii::app()->authManager;
            //предварительно удаляем старую связь
            $auth->revoke($this->prevRole, $this->id);
            $auth->assign($this->role, $this->id);
            $auth->save();
        }else{
            if($this->isNewRecord){ 
                $auth = Yii::app()->authManager;
                $auth->assign($this->role, $this->id);
                $auth->save();
            }
        }
        return true;
    }
    
    public function beforeDelete(){
        parent::beforeDelete();
        //убираем связь удаленного пользователя с ролью
        $auth = Yii::app()->authManager;
        $auth->revoke($this->role, $this->id);
        $auth->save();
        return true;
    }
}
