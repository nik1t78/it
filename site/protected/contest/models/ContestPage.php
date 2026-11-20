<?php

/**
 * This is the model class for table "{{contest_page}}".
 *
 * The followings are the available columns in table '{{contest_page}}':
 * @property integer $id
 * @property string $title
 * @property string $anons
 * @property string $body
 * @property string $code
 * @property integer $contest_id
 *
 * The followings are the available model relations:
 * @property Contest $contest
 */
class ContestPage extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ContestPage the static model class
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
		return '{{contest_page}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, code, contest_id', 'required'),
			array('contest_id', 'numerical', 'integerOnly'=>true),
			array('title, code', 'length', 'max'=>255),
			array('anons, body', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, anons, body, code, contest_id', 'safe', 'on'=>'search'),
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
			'contest' => array(self::BELONGS_TO, 'Contest', 'contest_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => 'Title',
			'anons' => 'Anons',
			'body' => 'Body',
			'code' => 'Code',
			'contest_id' => 'Contest',
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
		$criteria->compare('anons',$this->anons,true);
		$criteria->compare('body',$this->body,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('contest_id',$this->contest_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}