<?php

/** 
 * This is the model class for table "{{settings}}".
 * 
 * The followings are the available columns in table "{{settings}}"
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $description
 * @property integer $sort
 *  
 */

class Settings extends CActiveRecord{
        
    /**
     * Returns the static model of the specified AR class.
     * @return Settings the statistic model class
     */
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    
    /**
     * @return string the associated database table name
     */
    public function tableName(){
        return '{{settings}}';
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
            array('description', 'safe'),
            //array('password', 'required', 'on' =>'create'),
            //array('name, full_name', 'required'),
            //array('role', 'safe'),
            //array('name', 'required', 'on' => 'update'),
            //array('password', 'authenticate'), /** не понятно где эта функция находится... **/
            //array('email', 'email'),
			//array('author_id, template_id, module_id, category_id, menuOrder, mapOrder', 'numerical', 'integerOnly'=>true),
			//array('name, full_name', 'length', 'max'=>50),
            //array('email', 'length', 'max'=>20),
            //array('password', 'length', 'min'=>6, 'max'=>12),
            //array('password', 'compare', 'compareAttribute'=>'password2', 'on'=>'register'), /** при регистрации password должен быть равен password2 **/
			//array('anons, body, create_time, update_time, menuEnable, mapEnable', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			//array('name, email, full_name', 'safe', 'on'=>'search'),
        );
    }
    
    /**
     *
     * @return array relational rules. 
     */
    public function relations() {
        return array(
            'params' => array(self::HAS_MANY, 'SettingsParams', 'setting_id'),
        );
    }
    
    public function attributeLabels()
	{
		return array(
			'id' => Yii::t('settings', 'ID'),
			'name' => Yii::t('settings', 'Идентификатор'),
			'title' => Yii::t('settings', 'Название'),
			'description' => Yii::t('settings', 'Описание'),
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
                'order' => 'sort',
            ),
        );
    }
}