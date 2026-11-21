<?php

class IBlockSection extends IblockCategory{
    const ACTIVE = 'Y';
    const NON_ACTIVE = 'N';

    public $urlPrefix = 'iblock/';
    public $titleAttribute = 'name';
    public $aliasAttribute = 'code';
    public $parentAttribute = 'iblock_section_id';
    public $defaultCriteria = array('order'=>'t.sort ASC');
    public $requestPathAttribute = 'path';
    public $parentFrom = 0;

    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function tableName(){
        return '{{iblock_section}}';
    }

    public function rules(){
        return array(
            array('code, name', 'required'),
            array('code', 'length', 'max'=>50),
            array('name', 'length', 'max'=>255),
            array('active, description, description_type, detail_picture, iblock_id, iblock_section_id, picture, redirect', 'safe'),
            array('sort', 'numerical', 'integerOnly'=>true),
        );
    }

    public function relations() {
        return array(
            'iblock' => array(self::BELONGS_TO, 'IBlock', 'iblock_id'),
            'firstElement' => array(self::HAS_MANY, 'IBlockElement', 'iblock_section_id', 'order'=>'sort ASC'),
            'sectionCount' => array(self::STAT, 'IBlockSection', 'iblock_section_id'),
            'elementCount' => array(self::STAT, 'IBlockElement', 'iblock_section_id'),
            'elements' => array(self::HAS_MANY, 'IBlockElement', 'iblock_section_id'),
            'sections' => array(self::HAS_MANY, 'IBlockSection', 'iblock_section_id'),
            'parent' => array(self::BELONGS_TO, 'IBlockSection', 'iblock_section_id'),
        );
    }

    public function attributeLabels()
	{
		return array(
			'active' => Yii::t('iblock', 'Активен'),
			'global_active' => Yii::t('iblock', 'GLOBAL_ACTIVE'),
			'sort' => Yii::t('iblock', 'Сортировка'),
			'name' => Yii::t('iblock', 'Название'),
			'code' => Yii::t('iblock', 'Символьный код'),
			'picture' => Yii::t('iblock', 'Изображение'),
			'description' => Yii::t('iblock', 'Описание'),
			'description_type' => Yii::t('iblock', 'Тип описания'),
			'detail_picture' => Yii::t('iblock', 'Изображение для детальной информации'),
            'redirect' => Yii::t('iblock', 'Перенаправление'),
			'socnet_group_id' => Yii::t('iblock', 'SOCNET_GROUP_ID'),
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

    public function iblock_id($iblock_id)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.iblock_id=:iblock_id',
            'params' => array(':iblock_id'=>$iblock_id),
        ));
        return $this;
    }
    /**
     * Выбирает только активные разделы из БД
     * @return \IBlockElement
     */
    public function active(){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.active=:active',
            'params'=>array(':active'=>IBlockSection::ACTIVE),
        ));
        return $this;
    }

    public static function getList($iblock_id = 0){
        if($iblock_id == 0) return array();

        $list = self::getTree($iblock_id);

        return array('- Корень -') + Misc::formatMultiList($list);
    }

    public static function getTree($iblock_id=0, $section_id = 0){
        if($iblock_id == 0) return array();
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and iblock_section_id=:section_id';
        $criteria->params = array(
            ':iblock_id'=>$iblock_id,
            ':section_id' => $section_id
        );
        $criteria->order = 'sort ASC';

        $model = IBlockSection::model()->findAll($criteria);
        $result = array();
        foreach($model as $section){
            $result[$section->id]['name'] = $section->name;
            $result[$section->id]['items'] = self::getTree($iblock_id, $section->id);
        }
        return $result;
    }

    public static function getModelTree($iblock_id=0, $section_id=0)
    {
        if ($iblock_id == 0) return array();
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and iblock_section_id=:section_id';
        $criteria->params = array(
            ':iblock_id'=>$iblock_id,
            ':section_id'=>$section_id
        );
        $criteria->order = 'sort ASC';
        $model = IBlockSection::model()->findAll($criteria);
        $result = array();
        foreach ($model as $section) {
            $result[$section->id]['model'] = $section;
            $result[$section->id]['items'] = self::getModelTree($iblock_id, $section->id);
        }
        return $result;
    }

    private $_url;

    public function getUrl(){
        if($this->_url === null)
            //$this->_url = Yii::app()->createUrl('iblock/default/view', array('section'=>$this->cache(3600)->getPath(), 'iblock'=>$this->iblock->code));
            $this->_url = '/iblock/' . $this->iblock->code . '/' . $this->path . '/';
        return $this->_url;
    }

    public function afterSave() {
        parent::afterSave();

            if(!empty($_POST['IBlockSection']['properties'])){
                $properties = $_POST['IBlockSection']['properties'];

                foreach($properties as $id=>$property){
                    $propModel = IBlockProperty::model()->findByPk($id);

                    if($propModel->section_id !== $this->id){                               // Если свойство принадлежит не тек. разделу, а инфоблоку
                        $diffArray = array_diff_assoc($property, $propModel->attributes);
                        if(!empty($diffArray)){                                             // Сравниваем массив полей из формы с массивом атрибутов из БД. Если массив пуст, значит
                                                                                            // все значения совпадают. Если не пуст - значит какое-то значение было изменено
                            $iblock_id = $propModel->iblock_id;
                            $propModel = new IBlockProperty;
                            $propModel->iblock_id = $iblock_id;
                            $propModel->section_id = $this->id;
                        }
                    }

                    if(isset($property['delete']) && $property['delete'] == 1){         //Если установлен атрибут delete - удаляем запись и не выполняем дальнейшее присваивание атрибутов
                        if(!$propModel->isNewRecord) $propModel->delete();
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
            if(!empty($_POST['IBlockSection']['new_properties'])){
                $new_properties = $_POST['IBlockSection']['new_properties'];

                foreach ($new_properties as $property){
                    $newProperties = new IBlockProperty;
                    $tmpName = trim($property['name']);
                    $tmpCode = trim($property['code']);
                    if(empty($tmpName) || empty($tmpCode)) continue;

                    if(isset($property['delete'])&& $property['delete'] == 1) continue;

                    $newProperties->attributes = $property;
                    $newProperties->section_id = $this->id;
                    $newProperties->iblock_id = $this->iblock_id;
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
