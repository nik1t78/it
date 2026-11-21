<?php

class Filemanager extends CFormModel{
    public $uploadfiles;
    public $uploadpath;
    
    public static function model($className=__CLASS__){
        return parent::model($className);
    }
    /**
    public function tableName(){
        return '{{users}}';
    }
    **/
    public function rules(){
        return array(
            array('uploadfiles', 'file', 'types'=>'jpg, gif, png'),
            array('uploadpath', 'safe'),

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
    
    public function attributeLabels()
	{
		return array(
			'id' => Yii::t('users', 'ID'),
		);
	}
    
    /**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
/**	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('full_name',$this->full_name,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}**/
    
}