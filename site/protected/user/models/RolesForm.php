<?php

class RolesForm extends CFormModel
{
        public $name;
        public $description;
        public $rules;
	private $_auth;
	
	public function rules()
	{
		return array(
			// username and password are required
			array('name', 'required'),
                        array('description', 'safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'name'=> Yii::t('user', 'Название роли'),
			'description' => Yii::t('user', 'Описание'),
			'rules' => Yii::t('user', 'Набор правил'),
		);
	}

    
        public function getPermissions(){        
            $this->_auth = Yii::app()->authManager;

            $roles = $this->_auth->roles;
            $tasks = $this->_auth->tasks;
            
            $items = $this->getStructure($tasks);

            return array(
                'roles' => $roles,
                'items' => $items
            );
        }

        private function getStructure($items){
            $tmp = array();
            foreach($items as $name=>$val){
                $tmp[$name] = array(
                    'description' => $val->description,
                    'bizRule' => $val->bizRule,
                    'active' => false,
                    'items' => $this->getStructure($this->_auth->getItemChildren($name))
                );
            }
            return $tmp;
        }
}
