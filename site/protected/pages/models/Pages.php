<?php

class Pages extends Category{

    public $parentId;
    public $urlPrefix = '';
    public $titleAttribute = 'title';
    public $aliasAttribute = 'name';
    public $parentAttribute = 'parent_id';
    public $defaultCriteria = array('order'=>'t.lft ASC');
    public $requestPathAttribute = 'path';
    public $urlAttribute = 'purl';
    public $parentRelation = 'parent';
    public $parentFrom = 1;

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return '{{pages}}';
    }

    public function rules(){
        return array(
            array('title, name', 'required'),
            array('author_id, module_id, parent_id, menu_order, map_order', 'numerical', 'integerOnly'=>true),
            //array('parent_id', 'exist', 'className'=>__CLASS__, 'attributeName'=>'id'),
            /* Применение статусов страниц - активно/неактивно
             * array('status', 'in', 'range'=>array_keys($this->getStatusList())),
             * ...
             * const STATUS_PUBLISH = 1;
             * const STATUS_DRAFT = 2;
             *
             * public static function getStatusList($key = null){
             *      $arr = array(
             *          self::STATUS_PUBLISH => 'Publish',
             *          self::STATUS_DRAFT => 'Draft',
             *      );
             *
             *      return $key === null ? $arr : $arr[$key];
             * }
             *
             */
            array('title, redirect, name, template_id', 'length', 'max'=>255),
            array('anons, body, create_time, update_time, menu_enable, map_enable, level, lft, rgt, permissions', 'safe'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('title, anons, body', 'safe', 'on'=>'search'),
        );
    }


    private $_purl;

    public function getPurl(){
        if($this->_purl === null)
            $this->_purl = Yii::app()->request->baseUrl.'/'.$this->urlPrefix . $this->cache(3600)->getPath() . Yii::app()->urlManager->urlSuffix;
            //$this->_purl = Yii::app()->createUrl('pages/default/view', array('section'=>$this->cache(3600)->getPath()));
        return $this->_purl;
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
            // NOTE: you may need to adjust the relation name and the related
            // class name for the relations automatically generated below.
            return array(
                'author' => array(self::BELONGS_TO, 'Users', 'author_id'),
                'menu' => array(self::MANY_MANY, 'Menu', 'tbl_menu_items(item_id, menu_id)'),
                'parent' => array(self::BELONGS_TO, __CLASS__, 'parent_id'),
            );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
            return array(
		'id' => Yii::t('pages', 'ID'),
                'name' => Yii::t('pages', 'Имя файла (англ)'),
		'title' => Yii::t('pages', 'Название'),
		'anons' => Yii::t('pages', 'Анонс'),
		'body' => Yii::t('pages', 'Текст страницы'),
		'create_time' => Yii::t('pages', 'Дата создания'),
		'update_time' => Yii::t('pages', 'Последнее обновление'),
		'author_id' => Yii::t('pages', 'Автор'),
		'template_id' => Yii::t('pages', 'Шаблон'),
		'module_id' => Yii::t('pages', 'Модуль'),
		'menu_order' => Yii::t('pages', 'Порядок в меню'),
		'menu_enable' => Yii::t('pages', 'Присутствие в меню'),
		'map_order' => Yii::t('pages', 'Порядок на карте'),
		'map_enable' => Yii::t('pages', 'Присутствие в карте сайта'),
		'redirect' => Yii::t('pages', 'URL перенаправления'),
                'parent_id' => Yii::t('pages', 'Расположение'),
                'level' => Yii::t('pages', 'Уровень в дереве'),
                'lft' => 'left',
                'rgt' => 'right',
                'url'=> Yii::t('pages', 'Замена URL'),
                'permissions' => Yii::t('pages', 'Права для редактирования'),
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

		$criteria->compare('title',$this->title,true);
		$criteria->compare('anons',$this->anons,true);
		$criteria->compare('body',$this->body,true);

                $criteria->order = $this->tree->hasManyRoots
                            ?$this->tree->rootAttribute . ', ' . $this->tree->leftAttribute
                            :$this->tree->leftAttribute;

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    /**
     *  Именованные группы условий представляет собой именованный критерий запроса, который можно использовать с другими группами и применять к запросам AR.
     *  @return Возвращает массив с группами условий
     */
    public function scopes(){
        return array(
            'order_by_id'=>array(
                'order'=>'id ',
            ),
        );
    }

    public function visibleMenu(){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.menu_enable=:active',
            'params'=>array(':active'=>1),
        ));
        return $this;
    }

    public function orderMenu(){
        $this->getDbCriteria()->order = 't.menu_order ASC';
        return $this;
    }

    /** Nested sets **/
    public function behaviors(){
        return array(
            'tree'=>array(
                'class' => 'application.extensions.tree.TreeView',
                'leftAttribute' => 'lft',
                'rightAttribute' => 'rgt',
                'levelAttribute' => 'level',
            ),
            'CategoryTreeBehavior' => array(
                'class' => 'DCategoryTreeBehavior',
                'titleAttribute' => $this->titleAttribute,
                'aliasAttribute' => $this->aliasAttribute,
                'urlAttribute' => $this->urlAttribute,
                'requestPathAttribute' => $this->requestPathAttribute,
                'parentAttribute' => $this->parentAttribute,
                'parentRelation' => $this->parentRelation,
                'defaultCriteria' => $this->defaultCriteria,
                'parentFrom' => $this->parentFrom,
            ),
        );
    }

    public function getLinkActive()
    {
        return mb_strpos(Yii::app()->request->pathInfo, $this->path, null, 'UTF-8') === 0;
    }

    public function getNameExt(){
        //это нужно для наглядности дерева в контролах CDropdownList и CListBox
        return str_repeat('--', $this->level - 1 ).' '.$this->title;
    }

    public static function getTreeIcon($data){
        if($data->module_id == -1) return "root";
        else if($data->module_id == 0) return "dir";
        else if($data->module_id == 1) return "page";
    }


    protected function afterSave(){
        parent::afterSave();


    }

    protected function afterDelete(){
    	parent::afterDelete();

    	MenuItems::model()->deleteAll('item_id='.$this->id);
    }



    /** Refactoring **/
    static public function routeUrl($uri, $forMenu=false)
    {
        $page = Pages::model()->findByPath($uri);
        if($page == null) return false;

        if($forMenu){
            return (int) $page->id;
        }else{
            $_GET['id'] = $page->id;
            return '/pages/default/view';
        }
    }

    public function getPage($id){
        $page = $this->findByPk($id);
        if($page===null)
            throw new CHttpException(404, Yii::t('pages', 'Страница не найдена'));

        return $page;
    }

    public function getParents($id = false){
        if ($id)
            $page = $this->findByPk($id);
        else
            $page = $this;

        if($page === null) return null;
        $parents = $page->ancestors()->findAll();

        return $parents;
    }

    public function getSiblings($id){
        $page = $this->findByPk($id);
        if($page === null) return null;
        $parent = $page->parent()->find();
        $siblings = $parent->children()->findAll('id!=:id', array(':id'=>$id));

        return $siblings;
    }

    public function getChildren($id, $forMenu=false){
        $page = $this->findByPk($id);
        if($page === null) return null;
        $criteria = new CDbCriteria;
        $criteria->order = 'menu_order ASC';
        $children = $page->children()->findAll($criteria);

        if($forMenu){
            /* TODO: Вынести преобразование AR в массив для меню в отдельный метод */
            $tmpChildren = array();
            foreach($children as $item){
                $link = $item->redirect != null ? $item->redirect : $this->getUrlByPage($item->id);
                $tmpChildren[] = array('label'=>$item->title, 'url'=>array($link));
            }
            $children = $tmpChildren;
        }
        return $children;
    }


    public function getUrlByPage($id){
        $page = $this->findByPk($id);
        $url = '';

        $parents = $this->getparents($id);

        foreach($parents as $parent){
            if($parent->level == 1) continue;
            $url .= '/'.$parent->name;
        }
        $url .= '/'.$page->name.'/';

        return $url;
    }


    /**
     * Returns items for zii.widgets.CMenu widget
     * @param mixed $parent number, object or array of numbers
     * @param int $sub levels
     * @return array
     */
    public function getMenuList($sub=0, $parent=0)
    {
        //$criteria = $this->getOwnerCriteria();
        $criteria = $this->getDbCriteria();
        if (!$parent)
            $parent = $this->getOwner()->getPrimaryKey();

        if ($parent)
            $criteria->compare($this->primaryKeyAttribute, $this->getChildsArray($parent));

        $items = $this->cached($this->getOwner())->findAll($criteria);

        $categories = array();
        foreach ($items as $item){
            $categories[$item->{$this->parentAttribute}][] = $item;
        }

        return $this->_getMenuListRecursive ($categories, $parent, $sub);
    }

    protected function _getMenuListRecursive($items, $parent, $sub)
    {
        $parent = (int)$parent;
        $resultArray = array();
        if (isset($items[$parent]) && $items[$parent]){
            foreach ($items[$parent] as $item){
                $resultArray[$item->getPrimaryKey()] = array(
                        'id'=>$item->getPrimaryKey(),
                        'label'=>$item->{$this->titleAttribute},
                        'url'=> (!empty($item->redirect)) ? $item->redirect : $item->{$this->urlAttribute},
                        'icon'=>$this->iconAttribute !== null ? $item->{$this->iconAttribute} : '',
                        'itemOptions'=>array('class'=>'item_' . $item->getPrimaryKey()),
                        'active'=>$item->{$this->linkActiveAttribute},
                    ) + ($sub ? array('items'=>$this->_getMenuListRecursive($items, $item->getPrimaryKey(), $sub - 1)) : array());
            }
        }
        return $resultArray;
    }
}
