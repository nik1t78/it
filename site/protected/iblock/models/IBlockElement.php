<?php

class IBlockElement extends CActiveRecord{
    const ACTIVE = 'Y';
    const NON_ACTIVE = 'N';

    public $urlPrefix = 'iblock';
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return '{{iblock_element}}';
    }

    public function rules(){
        return array(
            array('code, name', 'required'),
            array('code, name', 'length', 'max'=>255),
            array('active, published, preview_picture, preview_text_type, preview_text, detail_text, detail_text_type, detail_picture, iblock_id, iblock_section_id, active_from, rss_enable, redirect', 'safe'),
            array('sort', 'numerical', 'integerOnly'=>true),
        );
    }

    public function relations(){
        return array(
            'section' => array(self::BELONGS_TO, 'IBlockSection', 'iblock_section_id'),
            'iblock' => array(self::BELONGS_TO, 'IBlock', 'iblock_id'),
            'properties' => array(self::HAS_MANY, 'IBlockElementProperty', 'iblock_element_id'),
        );
    }

    public function attributeLabels()
    {
		return array(
                        'id' => Yii::t('iblock', 'id'),
                        'timestamp_x' => Yii::t('iblock', 'timestamp_x'),
                        'modified_by' => Yii::t('iblock', 'modified_by'),
                        'date_create' => Yii::t('iblock', 'date create'),
                        'created_by' => Yii::t('iblock', 'created_by'),
                        'iblock_id' => Yii::t('iblock', 'iblock id'),
                        'iblock_section_id' => Yii::t('iblock', 'iblock section id'),
                        'searchable_content' => Yii::t('iblock', 'searchable_content'),
                        'wf_status_id' => Yii::t('iblock', 'wf_status_id'),
                        'wf_parent_element_id' => Yii::t('iblock', 'wf_parent_element_id'),
                        'wf_new' => Yii::t('iblock', 'wf_new'),
                        'wf_locked_by' => Yii::t('iblock', 'wf_locked_by'),
                        'wf_date_lock' => Yii::t('iblock', 'wf_date_lock'),
                        'wf_comments' => Yii::t('iblock' ,'wf_comments'),
                        'in_sections' => Yii::t('iblock', 'in_sections'),
                        'xml_id' => Yii::t('iblock', 'xml_id'),
                        'tmp_id' => Yii::t('iblock', 'tmp_id'),
                        'wf_last_history_id' => Yii::t('iblock', 'wf_last_history_id'),
                        'show_counter' => Yii::t('iblock', 'show_counter'),
                        'show_counter_start' => Yii::t('iblock', 'show_counter_start'),
			'active' => Yii::t('iblock', 'Активен'),
			'active_from' => Yii::t('iblock', 'Дата начала'),
			'active_to' => Yii::t('iblock', 'Дата окончания'),
			'sort' => Yii::t('iblock', 'Сортировка'),
			'name' => Yii::t('iblock', 'Название'),
			'code' => Yii::t('iblock', 'Символьный код'),
			'preview_picture' => Yii::t('iblock', 'Изображение для анонса'),
			'preview_text' => Yii::t('iblock', 'Текст анонса'),
			'preview_text_type' => Yii::t('iblock', 'Тип анонса (текст, html)'),
			'detail_picture' => Yii::t('iblock', 'Изображение для детальной информации'),
			'detail_text' => Yii::t('iblock', 'Детальная информация'),
			'detail_text_type' => Yii::t('iblock', 'Тип детальной информации (текст, html)'),
			'tags' => Yii::t('iblock', 'Теги'),
                        'rss_enable' => Yii::t('iblock', 'Публикация в RSS'),
                        'published' => Yii::t('iblock', 'Опубликовать'),
            'redirect' => Yii::t('iblock', 'Перенаправление'),
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
     * Именованая группа условий. Выбирает всех потомков указанного родителя
     * @param type $parent_id
     * @return \IBlockSection
     */
    public function child($parent_id){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.iblock_section_id=:parent_id',
            'params' => array(':parent_id'=>$parent_id),
        ));
        return $this;
    }

    public function rss_enable()
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.rss_enable=:active',
            'params' => array(':active'=>IBlockElement::ACTIVE)
        ));
        return $this;
    }

    public function byProperty($propCode='', $propVal='', $operator = 'AND'){
        if(empty($propCode)) return $this;
        $property = IBlockElementProperty::model()->findAll(array(
            'select' => 'iblock_element_id',
            'condition' => 't.iblock_property_id IN (SELECT id FROM {{iblock_property}} p WHERE p.code=:propCode) AND t.value=:propVal',
            'params' => array(
                ':propCode' => $propCode,
                ':propVal' => $propVal
            )
        ));
        //if(empty($property)) return $this;

        $elsId = array();
        foreach($property as $prop){
            $elsId[] = $prop->iblock_element_id;
        }
        $this->getDbCriteria()->addInCondition('id', $elsId, $operator);
        return $this;
    }

    /**
     * Выбирает только активные элементы из БД
     * @return \IBlockElement
     */
    public function active($operator = 'AND'){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.active=:active AND t.published=:active',
            'params'=>array(':active'=>IBlockElement::ACTIVE),
        ), $operator);
        return $this;
    }

    public function getList($iblock_id = false){
            if($iblock_id && is_int($iblock_id)){
                $els = $this->model()->findAll('iblock_id=:iblock_id', array(':iblock_id'=>$iblock_id));
            }else{
                $els = $this->model()->findAll();
            }
            return $els;
    }

    public function getElement($id){
            if(is_int($id)){
                $element = $this->model()->findByPk($id);
            } elseif(is_string($id)){
                $element = $this->model()->find('code=:code', array(':code'=>$id));
            }

            return $element;
    }

    public function afterSave() {
            parent::afterSave();

            if(!empty($_POST['IBlockElement']['properties'])){
                $properties = $_POST['IBlockElement']['properties'];
                //VarDumper::dump($properties);

                foreach($properties as $code=>$value){
                    $count = count($value); // приравниваем кол-во вставляемых значений свойства к кол-ву переданных значений
                    if(!is_array($value)){
                        $value = trim($value);
                        $count = 1;                 // количество вставляемых значений свойства
                    }
                    //if(empty($value)) continue; // Игнорируем пустые свойства

                    $propModel = IBlockProperty::model()->find('code=:code', array(':code'=>$code));
                    if($propModel == null)
                        throw new CDbException(Yii::t('iblock', 'Свойство с кодом "'.$code.'" не найдено.'));

                    if ($count > 1) { //Если мультисвойство, нужно удалить все занчения и заново создать, чтобы обработать удаление одного из значений свойства
                        IBlockElementProperty::model()->deleteAll('iblock_element_id=:target_id and iblock_property_id=:property_id', array(
                            ':target_id' => $this->id,
                            ':property_id' => $propModel->id,
                        ));
                    }

                    $propTarget = IBlockElementProperty::model()->findAll('iblock_element_id=:element_id and iblock_property_id=:property_id', array(
                        ':element_id'=> $this->id,
                        ':property_id'=> $propModel->id,
                    ));

                    for($i = 0; $i < $count; $i++){
                        if(!isset($propTarget[$i])) { // создаем недостающие значения
                            $propTarget[$i] = new IBlockElementProperty;
                            $propTarget[$i]->iblock_element_id = $this->id;
                            $propTarget[$i]->iblock_property_id = $propModel->id;
                        }

                        if(is_array($value)){
                            if(empty($value[$i])) {
                                if (isset($propTarget[$i]) && !$propTarget[$i]->isNewRecord)
                                    $propTarget[$i]->delete();
                                continue;
                            }
                            $propTarget[$i]->value = $value[$i];
                        }else{
                            $propTarget[$i]->value = $value;
                        }
                        $propTarget[$i]->save();
                    }
                }

            }
    }

    private $_url;

    public function getUrl(){
        if($this->_url === null){
            if($this->section){
                $this->_url = '/iblock/' . $this->iblock->code . '/' . $this->section->path . '/e/' . $this->code . '/';
            }else{
                $this->_url = '/iblock/' . $this->iblock->code . '/e/' . $this->code . '/';
            }
            //$this->_url = Yii::app()->request->baseUrl . '/' . $this->urlPrefix . $this->section->path . '/' .$this->id;
        }
        return $this->_url;
    }
}
