<?php

/**
 * This is the model class for table "{{banner}}".
 *
 * The followings are the available columns in table '{{banner}}':
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $body
 */
class Banner extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Banner the static model class
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
		return '{{banner}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('title, url, image, description', 'length', 'max'=>255),
                        array('position, sort', 'numerical', 'integerOnly'=>true),
                        array('sort', 'default', 'value'=>'100'),
			//array('body, url', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, position, url, image', 'safe', 'on'=>'search'),
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
                    'pos' => array(self::BELONGS_TO, 'BannerPosition', 'position'),
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
                        'url' => 'Ссылка',
                        'sort' => 'Порядок',
                        'image' => 'Изображение',
                        'position' => 'Расположение',
                        'description' => 'Описание',
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
/*
		$criteria->compare('id',$this->id);
		$criteria->compare('title',$this->code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('body',$this->body,true);
*/
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}