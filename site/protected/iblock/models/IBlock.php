<?php

class IBlock extends CActiveRecord{
    const ACTIVE = 'Y';
    const NOT_ACTIVE = 'N';

    public static $textType = array(
        'text' => 'Текст',
        'html' => 'HTML',
    );

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return '{{iblock}}';
    }

    public function rules(){
        return array(
            array('code, name', 'required'),
            array('code', 'length', 'max'=>50),
            array('name, template', 'length', 'max'=>255),
            array('active, description_type, description, index_element, index_section,
                sections_name, section_name, elements_name, element_name, section_add, section_edit, section_delete, element_add, element_edit, element_delete, show_all_elements', 'safe'),
            array('sort, page_count', 'numerical', 'integerOnly'=>true),
        );
    }

    public function relations() {
        return array(
            'sections' => array(self::HAS_MANY, 'IBlockSection', 'iblock_id'),
            'sectionCount' => array(self::STAT, 'IBlockSection', 'iblock_id'),
            'elementCount' => array(self::STAT, 'IBlockElement', 'iblock_id'),
        );
    }

    public function attributeLabels()
	{
		return array(
			'id' => Yii::t('iblock', 'ID'),
                        'timestamp_x' => Yii::t('iblock', 'timestamp'),
			'iblock_type_id' => Yii::t('iblock', 'Тип инфоблока'),
			'lid' => Yii::t('iblock', 'LID'),
			'code' => Yii::t('iblock', 'Символьный код'),
			'name' => Yii::t('iblock', 'Название инфоблока'),
			'active' => Yii::t('iblock', 'Активен'),
			'sort' => Yii::t('iblock', 'Сортировка'),
			'list_page_url' => Yii::t('iblock', 'URL страницы информационного блока'),
			'detail_page_url' => Yii::t('iblock', 'URL страницы детального просмотра'),
			'section_page_url' => Yii::t('iblock', 'URL страницы раздела'),
			'picture' => Yii::t('iblock', 'Изображение'),
			'description' => Yii::t('iblock', 'Описание'),
			'description_type' => Yii::t('iblock', 'Тип описания (текст, html, виз. редактор)'),
            'rss_ttl' => Yii::t('iblock', 'rss ttl'),
            'rss_active' => Yii::t('iblock', 'rss active'),
            'rss_file_active' => Yii::t('iblock', 'rss file active'),
            'rss_file_days' => Yii::t('iblock', 'rss file days'),
            'rss_yandex_active' => Yii::t('iblock', 'rss yandex active'),
			'xml_id' => Yii::t('iblock', 'XML ID'),
            'tmp_id' => Yii::t('iblock', 'TMP ID'),
			'index_element' => Yii::t('iblock', 'Индексировать элементы для модуля поиска'),
			'index_section' => Yii::t('iblock', 'Индексировать разделы для модуля поиска'),
			'sections_name' => Yii::t('iblock', 'Название разделов'),
			'section_name' => Yii::t('iblock', 'Название раздела'),
			'elements_name' => Yii::t('iblock', 'Название элементов'),
			'element_name' => Yii::t('iblock', 'Название элемента'),
            'section_add' => Yii::t('iblock', 'Добавить раздел'),
            'section_edit' => Yii::t('iblock', 'Изменить раздел'),
            'section_delete' => Yii::t('iblock', 'Удалить раздел'),
            'element_add' => Yii::t('iblock', 'Добавить элемент'),
            'element_edit' => Yii::t('iblock', 'Изменить элемент'),
            'element_delete' => Yii::t('iblock', 'Удалить элемент'),
			'rights_mode' => Yii::t('iblock', 'Режим прав доступа'),
			'section_chooser' => Yii::t('iblock', 'Интерфейс привязки элемента к разделам'),
			'list_mode' => Yii::t('iblock', 'Режим просмотра разделов и элементов'),
			'workflow' => Yii::t('iblock', 'Участвует в документообороте'),
            'bizproc' => Yii::t('iblock', 'bizproc'),
            'version' => Yii::t('iblock', 'version'),
            'last_conv_element' => Yii::t('iblock', 'last_conv_element'),
            'socnet_group_id' => Yii::t('iblock', 'socnet_group_id'),
            'edit_file_before' => Yii::t('iblock', 'edit file before'),
            'edit_file_after' => Yii::t('iblock', 'edit file after'),
            'template' => Yii::t('iblock', 'Файл шаблона для инфоблока'),
            'show_all_elements' => Yii::t('iblock', 'Показывать все элементы в корне'),
            'page_count' => Yii::t('iblock', 'Количество элементов на страницу'),
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


    /**
     * Выбирает только активные инфоблоки из БД
     * @return \IBlockElement
     */
    public function active(){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.active=:active',
            'params'=>array(':active'=>IBlock::ACTIVE),
        ));
        return $this;
    }

    public static function getList(){
        $model = IBlock::model()->findAll();
        $list = array(0 => '-- Выберите инфоблок --');
        foreach($model as $iblock){
            $list[$iblock->id] = $iblock->name;
        }

        return $list;
    }

    public function afterSave() {
        parent::afterSave();

            if(!empty($_POST['IBlock']['properties'])){
                $properties = $_POST['IBlock']['properties'];

                foreach($properties as $id=>$property){
                    $propModel = IBlockProperty::model()->findByPk($id);

                    if(isset($property['delete']) && $property['delete'] == 1){
                        $propModel->delete();
                        continue;
                    }

                    $propModel->attributes = $property;

                    /*
                    echo 'Сохраняем существующее свойство '.$property['name']."<br>\n";
                    VarDumper::dump($property);
                    VarDumper::dump($propModel->attributes);
                    echo "<br><br>\n\n";
                    */
                    $propModel->save();
                }

            }
            if(!empty($_POST['IBlock']['new_properties'])){
                $new_properties = $_POST['IBlock']['new_properties'];
                foreach ($new_properties as $property){
                    $newProperties = new IBlockProperty;
                    $tmpName = trim($property['name']);
                    $tmpCode = trim($property['code']);
                    if(empty($tmpName) || empty($tmpCode)) continue;

                    if(isset($property['delete'])&& $property['delete'] == 1) continue;

                    $newProperties->attributes = $property;
                    $newProperties->iblock_id = $this->id;
                    /*
                    echo 'Сохраняем новое свойство '.$property['name']."<br>\n";
                    VarDumper::dump($property);
                    VarDumper::dump($newProperties->attributes);
                    echo "<br><br>\n\n";
                    */
                    $newProperties->save();
                }
            }
    }
}
