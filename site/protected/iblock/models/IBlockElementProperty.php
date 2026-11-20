<?php

class IBlockElementProperty extends CActiveRecord{
        
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    
    public function tableName(){
        return '{{iblock_element_property}}';
    }
    
    public function rules(){
        return array(
            
        );
    }
    
    public function relations(){
        return array(
            'property' => array(self::BELONGS_TO, 'IBlockProperty', 'iblock_property_id'),
        );
    }
    
    public function attributeLabels()
	{
		return array(
			'iblock_property_id' => Yii::t('iblock', 'ID параметра'),
			'iblock_element_id' => Yii::t('iblock', 'ID инфоблока'),
			'value' => Yii::t('iblock', 'Значение'),
			'value_type' => Yii::t('iblock', 'Тип значения'),	
			'value_enum' => Yii::t('iblock', 'Набор значений'),	
			'value_num' => Yii::t('iblock', 'Числовое значение'),
			'description' => Yii::t('iblock', 'Описание'),	
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

        public function byCode($propCode)
        {
            if (empty($propCode)) {
                return $this;
            }

            $this->getDbCriteria()->mergeWith(array(
                'condition' => 't.iblock_property_id = (SELECT id FROM {{iblock_property}} p WHERE p.code=:propCode)',
                'params' => array(
                    ':propCode' => $propCode
                )
            ));

            return $this;
        }
    
}