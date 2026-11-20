<?php
class PhpAuthManager extends CPhpAuthManager{
    public function init(){
        //Иерархия ролей расположена в auth.php в директории config
        if($this->authFile === null){
            $this->authFile = Yii::getPathOfAlias('application.config.auth').'.php';
        }
        
        parent::init();
        
        //Для гостей у нас и так роль по умолчанию guest.
        if(!Yii::app()->user->isGuest){
            //связываем роль, заданную в БД с идентификатором пользователя, возвращенным UserIdentity.getId().
            $this->assign(Yii::app()->user->role, Yii::app()->user->id);
        }
    }
}