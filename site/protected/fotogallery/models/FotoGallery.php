<?php

/**
 * This is the model class for table "{{fotogallery}}".
 *
 * The followings are the available columns in table '{{fotogallery}}':
 * @property integer $id
 * @property string $title
 * @property string $code
 * @property string $created_date
 * @property string $timestamp_x
 * @property string $description
 * @property string $picture
 * @property string $active
 * @property integer $user_id
 */
class FotoGallery extends CActiveRecord
{
    const ACTIVE = 'Y';
    const NOT_ACTIVE = 'N';
    
    private $_url;
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Fotogallery the static model class
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
		return '{{foto_gallery}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, user_id', 'required'),
			array('user_id, sort', 'numerical', 'integerOnly'=>true),
			array('title, code', 'length', 'max'=>255),
			array('active, show_in_list', 'length', 'max'=>1),
			array('created_date, description, picture', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, code, created_date, timestamp_x, description, picture, active, user_id', 'safe', 'on'=>'search'),
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
                    'sectionCount' => array(self::STAT, 'FotoSection', 'gallery_id'),
                    'elementCount' => array(self::STAT, 'Foto', 'gallery_id'),
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
			'created_date' => 'Дата создания',
			'timestamp_x' => 'Timestamp X',
			'description' => 'Описание',
			'picture' => 'Изображение',
			'active' => 'Активен',
			'user_id' => 'Автор',
                        'sort' => 'Порядок',
                        'show_in_list' => 'Выводить в списке галерей'
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
		$criteria->compare('created_date',$this->created_date,true);
		$criteria->compare('timestamp_x',$this->timestamp_x,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('picture',$this->picture,true);
		$criteria->compare('active',$this->active,true);
		$criteria->compare('user_id',$this->user_id);

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
        
        public function getUrl() 
        {
            if ($this->_url === null) {
                $this->_url = Yii::app()->createUrl('fotogallery/default/view', array('gallery'=>$this->id));
            }
            return $this->_url;
        }
}