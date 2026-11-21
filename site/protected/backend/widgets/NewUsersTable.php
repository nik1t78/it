<?php

class NewUsersTable extends CWidget{
    public $result = array();
    
    public function run(){    
        $criteria = new CDbCriteria;
        $criteria->condition = 'role=:role';
        $criteria->params = array(
            ':role' => 'manager'
        );
        $criteria->limit = 50;
        $criteria->order = 'id DESC';
        
        $newUsers = User::model()->findAll($criteria);
        
        foreach ($newUsers as $user){
            
            $this->result[] = array(
                'id' => $user->id,
                'login' => $user->login,
                'name' => $user->full_name,
                'role' => User::getRole($user->id),
                'email' => $user->email,
                'phoneNum' => $user->phoneNum
            );        
        }
       
        $this->render('newUsersTable');        
    }
    
}
