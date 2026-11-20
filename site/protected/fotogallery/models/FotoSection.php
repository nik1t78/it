<?php

/**
 * This is the model class for table "{{foto_section}}".
 *
 * The followings are the available columns in table '{{foto_section}}':
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property integer $gallery_id
 */
class FotoSection extends CActiveRecord
{
    private $_url;
    /**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return FotoSection the static model class
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
		return '{{foto_section}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, gallery_id', 'required'),
			array('gallery_id, section_id, sort', 'numerical', 'integerOnly'=>true),
			array('title, code', 'length', 'max'=>255),
			array('active', 'length', 'max'=>1),
			array('description, picture', 'safe'),
                        array('section_id', 'compare', 'compareAttribute'=>'id', 'operator'=>'!='),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, code, gallery_id', 'safe', 'on'=>'search'),
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
                    'parent' => array(self::BELONGS_TO, 'FotoSection', 'section_id'),
                    'sectionCount' => array(self::STAT, 'FotoSection', 'section_id'),
                    'elementCount' => array(self::STAT, 'Foto', 'section_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Название',
			'code' => 'Символьный код',
			'gallery_id' => 'Галерея',
                        'description' => 'Описание',
                        'active' => 'Активен',
                        'section_id' => 'Родительский раздел',
                        'sort' => 'Порядок',
                        'picture' => 'Изображение',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('gallery_id',$this->gallery_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        /**
         * Выбор только активных элементов
         * @return type
         */
        public function active()
        {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => 't.active=:active',
                'params' => array(':active'=>FotoGallery::ACTIVE)
            ));
            
            return $this;
        }
        
    /**
     * Именованая группа условий. Выбирает всех потомков указанного родителя
     * @param type $parent_id
     * @return \IBlockSection
     */
    public function child($section_id){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.section_id=:parent_id',
            'params' => array(':parent_id'=>$section_id),
        ));
        return $this;
    }
        
        public function getUrl()
        {
            if ($this->_url === null){
                $this->_url = Yii::app()->createUrl('fotogallery/default/view', array('gallery'=>$this->gallery_id, 'section'=>$this->id));
            }
            return $this->_url;
        }
        
        
    /**
     * Constructs breadcrumbs for zii.widgets.CBreadcrumbs widget
     * @param bool $lastLink if you can have link in last element
     * @return array
     */
    public function getBreadcrumbs($lastLink=false)
    {
        if ($lastLink){
            $breadcrumbs = array($this->title => $this->url);
        } else {
            $breadcrumbs = array($this->title);
        }
        $page = $this;

        $i = 50;
        
        
        while ($i-- && $this->cached($page)->parent){
            $breadcrumbs[$page->parent->title] = $page->parent->url;
            $page = $page->parent;
        }
        return array_reverse($breadcrumbs);
    }
    
    protected function cached($model=null)
    {
        if ($model === null)
            $model = $this;

        $connection = $model->getDbConnection();
        return $model->cache($connection->queryCachingDuration);
    }
}