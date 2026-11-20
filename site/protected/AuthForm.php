<?php

/**
 * 
 *
 */



class AuthForm extends CWidget{

	public function init(){
		$form = new LoginForm;
		
		$this->render('loginWidget',array('model'=>$form));
	}
	
	public function run(){
	
	}
    
}
