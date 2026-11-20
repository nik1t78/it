<?php

class IBlockFields extends CActiveRecord{
        
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    
    public function tableName(){
        return '{{iblock_fields}}';
    }
    
    public function rules(){
        return array(
            
        );
    }
    
    public function attributeLabels()
	{
		return array(
			'iblock_id' => Yii::t('iblock', 'iblock_id'),
			'field_id' => Yii::t('iblock', 'field_id'),
                        'is_required' => Yii::t('iblock', 'Обязательный'),
                        'default_value' => Yii::t('iblock', 'Значение по-умолчанию'),
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
    
}