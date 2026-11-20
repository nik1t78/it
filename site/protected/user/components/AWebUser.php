<?php
Yii::import('user.models.User');

class AWebUser extends CWebUser{
    private $_model = null;
    private $_isSuiteUser = null;
    
    public function isSuiteUser() {
        if(isset($this->_isSuiteUser)) {
            return $this->_isSuiteUser;
        }
        
        if(!$this->isGuest) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'id=:id AND login=:name';
            $criteria->params = array(':id' => $this->id, ':name' => Yii::app()->user->name);
            $user = User::model()->find($criteria);
            if(isset($user)) {
                $this->_isSuiteUser = true;
                return true;
            } else {
                $this->_isSuiteUser = false;
            }
        }
        
        return false;
    }
    
    public function getRole(){
        if($user = $this->getModel()){
            return $user->role;
        }
    }
    
    private function getModel(){
        if(!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id, array('select' => 'role'));
        }
        return $this->_model;
    }
    
    public function beforeLogin($id, $states, $fromCookie){
        parent::beforeLogin($id, $states, $fromCookie);
        
        if($fromCookie){
            if(empty($states['user_key'])){
                return false;
            }
            
            $autoLoginKey = $states['user_key'];
            
            $user = User::model()->findByPk($id);
            if($user === null)
                throw new CException(Yii::t('auth', 'ОШибка при проверке cookie для авторизации.'));
            $dbKey = $user->cookie_key;
            
            return !empty($dbKey) && $dbKey === $autoLoginKey;            
        }
        return true;
    }
    
    
    /**
    public function isAuthenticated(){
        if(Yii::app()->user->isGuest)
            return false;
            
        $authData = $this->getAuthData();
        
        if($authData['username'] && isset($authData['role']) && $authData['id'] && $authData['loginTime'])
            return true;
            
        return false;
    }
    
    public function getAuthData(){
        return array(
            'username' => Yii::app()->user->getState('username'),
            'role' => Yii::app()->user->getState('role'),
            'id' => Yii::app()->user->getState('id'),
            'loginTime' => Yii::app()->user->getState('loginTime'),    
        );
    }
    
    public function isSuperUser(){
        if(!$this->isAuthenticated())
            return false;            
        
        $loginAdmTime = Yii::app()->user->getState('loginAdmTime');

        $isAdmin = Yii::app()->user->getState('isAdmin');

        if ($isAdmin == User::ACCESS_LEVEL_ADMIN && $loginAdmTime)        
            return true;        

        if(Yii::app()->user->getState('role') == 'administrator')
            return true;
            
        return false;
    }**/
}
