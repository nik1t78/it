<?php

/**
 * This is the model class for table "{{iblock_subscribe_list}}".
 *
 * The followings are the available columns in table '{{iblock_subscribe_list}}':
 * @property integer $iblock_id
 * @property string $active_date
 * @property integer $start_element
 * @property integer $end_element
 * @property string $success
 */
class IBlockSubscribeList extends CActiveRecord
{
    const SUCCESS = 'Y';
    const NO_SUCCESS = 'N';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return IblockSubscribeList the static model class
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
		return '{{iblock_subscribe_list}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('iblock_id, active_date, start_element, end_element', 'required'),
			array('iblock_id, start_element, end_element', 'numerical', 'integerOnly'=>true),
			array('success', 'length', 'max'=>1),
                        array('id', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('iblock_id, active_date, start_element, end_element, success', 'safe', 'on'=>'search'),
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
			'iblock_id' => 'Iblock',
			'active_date' => 'Active Date',
			'start_element' => 'Start Element',
			'end_element' => 'End Element',
			'success' => 'Success',
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

		$criteria->compare('iblock_id',$this->iblock_id);
		$criteria->compare('active_date',$this->active_date,true);
		$criteria->compare('start_element',$this->start_element);
		$criteria->compare('end_element',$this->end_element);
		$criteria->compare('success',$this->success,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}