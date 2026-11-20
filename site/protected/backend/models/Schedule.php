<?php

/**
 * This is the model class for table "{{schedule}}".
 *
 * The followings are the available columns in table '{{schedule}}':
 * @property integer $id
 * @property string $name
 * @property string $active_date
 * @property string $period
 * @property string $command
 * @property string $last_active
 */
class Schedule extends CActiveRecord
{
    const COMPLETE = 'Y';
    const NONCOMPLETE = 'N';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Schedule the static model class
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
		return '{{schedule}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, active_date, command', 'required'),
			array('name', 'length', 'max'=>255),
			array('period', 'length', 'max'=>100),
			array('last_active', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, active_date, period, command, last_active', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'active_date' => 'Active Date',
			'period' => 'Period',
			'command' => 'Command',
			'last_active' => 'Last Active',
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
		$criteria->compare('active_date',$this->active_date,true);
		$criteria->compare('period',$this->period,true);
		$criteria->compare('command',$this->command,true);
		$criteria->compare('last_active',$this->last_active,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}