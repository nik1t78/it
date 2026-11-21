<?php

/**
 * This is the model class for table "{{menu}}".
 *
 * The followings are the available columns in table '{{menu}}':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $manual
 */
class Menu extends CActiveRecord
{
        const ACTIVE = 'Y';
        const NOT_ACTIVE = 'N';
        const EXPANDED = 'Y';
        const NOT_EXPANDED = 'N';
        const AUTOFILL = 'Y';
        
	/**
	 * Returns the static model of the specified AR class.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{menu}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
            return array(
                array('name', 'required'),
		array('name', 'length', 'max'=>100),
		array('description', 'length', 'max'=>255),
            
                //array('manual', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('id, name, description, manual', 'safe', 'on'=>'search'),
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
			//'menuItems' => array(self::MANY_MANY, 'Pages', 'tbl_menu_items(menu_id, item_id)'),
                    'menuItems' => array(self::HAS_MANY, 'MenuItems', 'menu_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('menu', 'ID'),
			'name' => Yii::t('menu', 'Название'),
			'description' => Yii::t('menu', 'Описание'),
			'menuItems' => Yii::t('menu', 'Элементы меню'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function afterSave(){
            if(isset($_POST['MenuItems']['isset'])){
                $menuItems = $_POST['MenuItems']['isset'];
				
             //   $transaction = $newItem->dbConnection->beginTransaction();
             //   try{
                    foreach($menuItems as $id=>$newItem){
                        $item = MenuItems::model()->findByPk($id);		
                        $item->attributes = $newItem;	
			$item->save(false);
                    }
             //       $transaction->commit();
             //   }catch(Exception $e){
             //       $transaction->rollBack();
             //   }
				
            }
            if (isset($_POST['MenuItems']['new'])) {
                $menuItems = $_POST['MenuItems']['new'];

              //  $transaction = $newItem->dbConnection->beginTransaction();
              //  try{
                    foreach($menuItems as $item){
                        $newItem = new MenuItems;
                        $newItem->attributes = $item;
                        $newItem->menu_id = $this->id;
			$newItem->save(false);
                    }
             //       $transaction->commit();
             //   }catch(Exception $e){
             //       $transaction->rollBack();
             //   }
            }
            parent::afterSave();
	}
        
        
        /**
         * Получить пункты меню с id указанным в параметре
         * @param type $menuId 
         */
        public function getAllItems($menu_id, $activeOnly=true, $forMenu = false){
            
            $activeCondition = $activeOnly===true?' AND active="'.self::ACTIVE.'"':'';
            if(is_int($menu_id)){ //ищем по id
                /* TODO: Разобраться с реляционным запросом - если нет связанных записей - выводит ноль */
                /*
                $menu = $this->with(array(
                'menuItems' => array(
                    'condition' => $activeCondition,
                    'order' => 'menuItems.sort ASC',
                    ),
                ))->findByPk($menu_id);*/
                $menu = $this->findByPk($menu_id);
            }elseif(is_string($menu_id)){ //ищем по name
                /*
                $menu = $this->with(array(
                'menuItems' => array(
                    'condition' => $activeCondition,
                    'order' => 'menuItems.sort ASC',
                    ),
                ))->find('name=:name', array(':name'=>$menu_id));
                  
                 */
                $menu = $this->find('name=:name', array(':name' => $menu_id));
            }
            
            if($menu == null){
                new CDbException(Yii::t('menu', 'Запрашиваемое меню не найдено.'));
                return array();
            }
            
            //$items = $menu->menuItems;
            $criteria = new CDbCriteria;
            $criteria->condition = 'menu_id=:menu_id';
            $criteria->condition .= $activeCondition;
            $criteria->order = 'sort ASC';
            $criteria->params = array(':menu_id'=>$menu->id);
            
            $items = MenuItems::model()->findAll($criteria);
            $items = $items == null?array():$items;
            
            if($forMenu){
                /* TODO: Вынести преобразование AR в массив для меню в отдельный метод */
                /* TODO: Сделать рефакторинг */
                
                if($menu->fill === Menu::AUTOFILL){
                    $pages = new Pages;
                    $fillItems = $pages->getChildren(Yii::app()->params['firstPageId'], true);
                }
                
                $tmpItems = array();
                
                if(!empty($fillItems)) $tmpItems = CMap::mergeArray($tmpItems, $fillItems);
                
                foreach($items as $item){
                    $subitems = array();
                    if($item->exp === Menu::EXPANDED){
                        $pages = new Pages;
                        $page_id = $pages->routeUrl($item->link, true);
                        
                        $subitems = $pages->getChildren($page_id, true);
                    }
                    
                    $tmpItems[] = array('label'=>$item->title, 'url'=>array($item->link), 'itemOptions'=>array('class'=>$item->css), 'items'=>$subitems);
                }
                $items = $tmpItems;
            }
            return $items;
        }
}