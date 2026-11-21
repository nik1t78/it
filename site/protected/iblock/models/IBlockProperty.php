<?php

class IBlockProperty extends CActiveRecord{

    public $propertyTypes = array(
        'S' => 'Строка',
        'N' => 'Число',
        'L' => 'Список',
        'F' => 'Файл',
        'G' => 'Привязка к разделам',
        'E' => 'Привязка к элементам',
        'P' => 'Привязка к свойству',
        'I' => 'Изображение'
    );
    public $listTypes = array(
        'R' => 'Точка',
        'C' => 'Флажки',
        'L' => 'Список',
    );
    const ACTIVE = 'Y';
    const NOT_ACTIVE = 'N';

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return '{{iblock_property}}';
    }

    public function rules(){
        return array(
            array('code, name', 'required'),
            array('code', 'length', 'max'=>50),
            array('name', 'length', 'max'=>255),
            array('active, section_id, property_type, list_type, file_type, multiple, is_required, default_value, link_iblock_id, link_property_id, with_description, searchable, filtrable, hint, user_type, user_type_settings', 'safe'),
            array('sort, row_count, col_count, multiple_cnt', 'numerical', 'integerOnly'=>true),
        );
    }

    public function relations() {
        return array(
            'value' => array(self::HAS_MANY, 'IBlockElementProperty', 'iblock_property_id'),
            'enum' => array(self::HAS_MANY, 'IBlockPropertyEnum', 'iblock_property_id',
                'order' => 'enum.sort ASC'
             ),
            'link_property' => array(self::HAS_ONE, 'IBlockProperty', 'link_property_id'),
        );
    }
    public function attributeLabels()
	{
		return array(
			'timestamp_x' => Yii::t('iblock', 'Timestamp'),
			'iblock_id' => Yii::t('iblock', 'Инфоблок'),
			'name' => Yii::t('iblock', 'Название'),
			'active' => Yii::t('iblock', 'Активен'),
			'sort' => Yii::t('iblock', 'Сортировка'),
			'code' => Yii::t('iblock', 'Символьный код'),
			'default_value' => Yii::t('iblock', 'Значение по-умолчанию'),
			'property_type' => Yii::t('iblock', 'Тип свойства'),
			'row_count' => Yii::t('iblock', 'Количество строк списка'),
			'col_count' => Yii::t('iblock', 'Количество столбцов'),
			'list_type' => Yii::t('iblock', 'Тип списка'),
			'multiple' => Yii::t('iblock', 'Множественное'),
			'xml_id' => Yii::t('iblock', 'XMLID'),
			'file_type' => Yii::t('iblock', 'Типы файлов (расширения через запятую)'),
			'multiple_cnt' => Yii::t('iblock', 'Кол-во полей для множ. значений'),
			'tmp_id' => Yii::t('iblock', 'TMPID'),
			'link_iblock_id' => Yii::t('iblock', 'Инфоблок'),
            'link_property_id' => Yii::t('iblock', 'Свойство'),
			'with_description' => Yii::t('iblock', 'Выводить поле для описания значения'),
			'searchable' => Yii::t('iblock', 'Участвует в поиске'),
			'filtrable' => Yii::t('iblock', 'Фильтруемое'),
			'is_required' => Yii::t('iblock', 'Обязательное'),
			'version' => Yii::t('iblock', 'Версия'),
			'user_type' => Yii::t('iblock', 'User type'),
			'user_type_settings' => Yii::t('iblock', 'User type settings'),
			'hint' => Yii::t('iblock', 'Подсказка'),
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

        public function getEnum(){
            $result = array();
            $enum = $this->enum;
            foreach($enum as $value){
                $result[$value->id] = $value->value;
            }

            return $result;
        }


    static public function getList($iblock_id=0)
    {
        $criteria = new CDbCriteria;
        if ($iblock_id > 0) {
            $criteria->condition = 'iblock_property_id=:propId';
            $criteria->params = array(
                ':propId' => $iblock_id
            );
        }

        $model = IBlockProperty::model()->findAll($criteria);
        $list = array(0 => '-- Выберите свойство --');
        foreach($model as $prop){
            $list[$prop->id] = $prop->name;
        }

        return $list;
    }
}
