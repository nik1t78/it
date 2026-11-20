<?php

class IBlockPropertyEnum extends CActiveRecord{

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return '{{iblock_property_enum}}';
    }

    public function rules(){
        return array(
            array('value', 'required'),
            array('value', 'length', 'max'=>255),
            array('iblock_property_id', 'safe'),
            array('sort', 'numerical', 'integerOnly'=>true),
        );
    }

    public function attributeLabels()
	{
		return array(
			'iblock_property_id' => Yii::t('iblock', 'ID параметра'),
			'value' => Yii::t('iblock', 'Значение'),
			'def' => Yii::t('iblock', 'DEF'),
			'sort' => Yii::t('iblock', 'Сортировка'),
			'xml_id' => Yii::t('iblock', 'XML ID'),
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

		//$criteria->compare('id',$this->id);
		//$criteria->compare('name',$this->name,true);
		//$criteria->compare('full_name',$this->full_name,true);
		//$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    static public function getList($property_id=0)
    {
        $criteria = new CDbCriteria;
        if ($property_id > 0) {
            $criteria->condition = 'iblock_property_id=:propId';
            $criteria->params = array(
                ':propId' => $property_id
            );
        }

        $model = IBlockPropertyEnum::model()->findAll($criteria);
        $list = array(0 => '-- Выберите значение --');
        foreach($model as $prop){
            $list[$prop->id] = $prop->value;
        }

        return $list;
    }
}
