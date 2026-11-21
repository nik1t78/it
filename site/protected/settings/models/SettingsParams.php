<?php

/** 
 * This is the model class for table "{{settings_params}}".
 * 
 * The followings are the available columns in table "{{settings_params}}"
 * @property integer $id
 * @property integer $setting_id
 * @property string $name
 * @property string $title
 * @property string $value
 * @property string $code
 * @property integer $sort
 *  
 */

class SettingsParams extends CActiveRecord{
        
    /**
     * Returns the static model of the specified AR class.
     * @return SettingsParams the statistic model class
     */
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName(){
        return '{{settings_params}}';
    }
    
    /**
     *
     * @return array validation rules for model attributes.
     */
    public function rules(){
        return array(
            array('name', 'length', 'max'=>100),
            array('title', 'length', 'max'=>255),
            array('sort', 'numerical', 'integerOnly'=>true),
            array('value, code', 'safe'),
        );
    }
    
    /**
     *
     * @return array relational rules. 
     */
    public function relations() {
        return array(
            //'setting_id' => array(self::HAS_ONE, 'Settings', 'id'),
        );
    }
    
    public function attributeLabels()
	{
		return array(
			'id' => Yii::t('settings', 'ID'),
                        'setting_id' => Yii::t('settings', 'Набор параметров'),
			'name' => Yii::t('settings', 'Идентификатор'),
			'title' => Yii::t('settings', 'Название'),
			'value' => Yii::t('settings', 'Значение'),
                        'code' => Yii::t('settings', 'Код'),
			'sort' => Yii::t('settings', 'Порядок'),
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

	return new CActiveDataProvider($this, array(
		'criteria'=>$criteria,
	));
    }
    
    /**
     * Именованные группы условий
     * @return array 
     */
    public function scopes(){
        return array(
            'order_by_sort' => array(
                'order' => $this->getTableAlias().'.sort',
            ),
        );
    }
        
}