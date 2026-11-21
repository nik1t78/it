<?php

class UserIdentity extends CUserIdentity{
    private $_id;
    const ERROR_ROLE_INVALID=3;
    
    public function authenticate(){
        $user = User::model()->findByAttributes(array('login'=>$this->username));
        if($user === null)
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if(!$user->validatePassword($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else if($user->role === User::ROLE_BANNED)
        	$this->errorCode = self::ERROR_ROLE_INVALID;
        else{
            $this->_id = $user->id;
            $this->username = $user->login;
            $this->setState('id', $user->id);
            $this->setState('username', $user->login);
            $this->setState('full_name', $user->full_name);
            $this->setState('role', $user->role);
            $this->setState('email', $user->email);
            $this->setState('loginTime', time());
            
            /** аутентификация с использованием cookie **/
            $cookieKey = $this->generateKey();
            $this->setState('user_key', $cookieKey); // сохраняем ключ в cookie            
            User::model()->updateByPk($user->id, array('cookie_key'=>$cookieKey)); // сохраняем ключ в БД
                        
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
    
    public function getId(){
        return $this->_id;
    }
    
    /*
     * Генерирует уникальный хэш-код
     */
    protected function generateKey(){
        return md5(uniqid('cookie_',true));
    }
}
