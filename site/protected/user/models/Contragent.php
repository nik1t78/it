<?php

/**
 * This is the model class for table "{{contragent}}".
 *
 * The followings are the available columns in table '{{contragent}}':
 * @property integer $id
 * @property string $name
 * @property string $region
 * @property string $address
 * @property integer $tin
 * @property string $phoneNum
 * @property string $email
 */
class Contragent extends CActiveRecord
{
    const CONFIRM = 'Y';
    const NON_CONFIRM = 'N';
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Contragent the static model class
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
		return '{{contragent}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, region, tin', 'required'),
			array('tin', 'numerical', 'integerOnly'=>true),
			array('name, region', 'length', 'max'=>255),
                        array('tin', 'unique', 'on'=>'registration'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
                        array('address, phoneNum, email, confirm', 'safe'),
			array('id, name, region, tin', 'safe', 'on'=>'search'),
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
			'name' => 'Название',
			'region' => 'Регион',
			'tin' => 'ИНН',
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
		$criteria->compare('region',$this->region,true);
		$criteria->compare('tin',$this->tin);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
        
        
        /**
         * Возвращаем название компании по ее id
         */
        public function getContragentName($id){
            $contragent = $this->model()->findByPk($id);
            return $contragent->name;
        }
        
        static public function getContragentsList(){
            $obj = new self;
            $criteria = new CDbCriteria;
            $criteria->order = 'name ASC';
            $contragents = $obj->model()->findAll($criteria);
            $arr = array();
            foreach($contragents as $org){
                $arr[$org->id] = $org->name;
            }
            return $arr;
        }
        
        static public function searchByTin($tin){
            $criteria = new CDbCriteria;
            $criteria->compare('tin', $tin);
            
            $contragents = self::model()->findAll($criteria);
            $result = array();
            foreach($contragents as $org){
                $result[] = array(
                    'id' => $org->id,
                    'name' => $org->name,
                    'region' => $org->region,
                    'tin' => $org->tin
                );
            }
            return $result;
        }
        
        static public function getNameById($id){
            $org = self::model()->findByPk($id);
            if($org == null) return '';
            return $org->name;
        }
        
}