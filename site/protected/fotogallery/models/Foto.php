<?php

/**
 * This is the model class for table "{{foto}}".
 *
 * The followings are the available columns in table '{{foto}}':
 * @property integer $id
 * @property string $type
 * @property integer $user_id
 * @property integer $width
 * @property integer $height
 * @property string $alt
 * @property string $preview
 * @property string $key_photo
 * @property string $create
 * @property integer $gallery_id
 */
class Foto extends CActiveRecord
{
    const ACTIVE = 'Y';
    const NON_ACTIVE = 'N';
    public $createPreview; //Флажок "Создавать превью изображения"
    public $preview_prefix = 'preview_';
    public $preview_width = 100;
    public $preview_height = 100;
    public static $preview_crop = array(
        'resize' => 'Растягивать',
        'crop' => 'Обрезать'
    );
    
    private $_url;
    
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Foto the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{foto}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, image, gallery_id', 'required'),
            array('user_id, width, height, gallery_id, section_id', 'numerical', 'integerOnly'=>true),
            array('type', 'length', 'max'=>10),
            array('alt', 'length', 'max'=>250),
            array('onMain', 'length', 'max'=>1),
            array('preview, image', 'length', 'max'=>255),
            array('key_photo', 'length', 'max'=>150),
            array('created, type', 'safe'),
            array('image', 'unique'),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, type, user_id, width, height, alt, preview, key_photo, created, gallery_id', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'section' => array(self::BELONGS_TO, 'FotoSection', 'section_id'),
            'gallery' => array(self::BELONGS_TO, 'FotoGallery', 'gallery_id'),
            
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'type' => 'Тип изображения (full, big, previe)',
            'user_id' => 'Автор',
            'width' => 'Размер изображения',
            'height' => 'Высота',
            'alt' => 'Описание изображения',
            'preview' => 'Превью',
            'key_photo' => 'Key Photo',
            'created' => 'Дата создания',
            'gallery_id' => 'Галерея',
            'section_id' => 'Раздел галереи',
            'image' => 'Файл изображения',
            'createPreview' => 'Создавать превью',
            'preview_prefix' => 'Префикс имени файла',
            'preview_size' => 'Размер превью',
            'preview_crop' => 'Метод изменения размера',
            'onMain' => 'Выводить на главной'
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
        $criteria->compare('type',$this->type,true);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('width',$this->width);
        $criteria->compare('height',$this->height);
        $criteria->compare('alt',$this->alt,true);
        $criteria->compare('key_photo',$this->key_photo,true);
        $criteria->compare('created',$this->created,true);
        $criteria->compare('gallery_id',$this->gallery_id);
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }    
    /**
     * Выбирает только активные элементы из БД
     * @return \IBlockElement
     */
    public function active(){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.active=:active',
            'params'=>array(':active'=>Foto::ACTIVE),
        ));
        return $this;
    }
    
    /**
     * Создание превью изображения
     * @param string $original путь к файлу оригинального изображения относительно корня сайта
     * @param string $prefix префикс имени файла превью
     * @param integer $width ширина превью
     * @param integer $height высота превью
     * @param string $resize_type тип сжатия файла: resize - сжать, crop - обрезать
     * @return string путь к файлу превью относительно корня сайта
     */
    public function createPreview($original, $prefix, $width=0, $height=0, $resize_type = 'resize')
    {
        $tmp = explode('/', $original);
        $original_name = array_pop($tmp);
        $original_path = implode('/', $tmp).'/';
        
        if ($width == 0 && $height == 0){
            $width = $this->preview_width;
            $height = $this->preview_height;
        } elseif ($width == 0 && $height > 0){
            $width = $height;
        } elseif ($height == 0 && $width > 0){
            $height = $width;
        }
        
        //Файл не найден
        if(!file_exists(Yii::getPathOfAlias('webroot').$original)) 
            return false;
        
        $original_file = Yii::app()->image->open(Yii::getPathOfAlias('webroot').$original);
        
        // Метод создания превью: уменьшение или обрезка
        if($resize_type == 'resize')
            $mode = Imagine\Image\ImageInterface::THUMBNAIL_INSET;
        else
            $mode = Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
        
        $thumbnail = $original_file->thumbnail(new Imagine\Image\Box($width, $height), $mode);
        $thumbnail->save(Yii::getPathOfAlias('webroot').$original_path.$prefix.$original_name);
                
                
        return $original_path.$prefix.$original_name;
    }
    
    public function beforeSave(){
        parent::beforeSave();
        
        $this->created = date('Y-m-d H:i:s');
        return true;
    }
        
    /**
     * Именованая группа условий. Выбирает всех потомков указанного родителя
     * @param type $parent_id
     * @return \IBlockSection
     */
    public function child($section_id){
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.section_id=:parent_id',
            'params' => array(':parent_id'=>$section_id),
        ));
        return $this;
    }
    
    public function getUrl()
    {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('fotogallery/default/element', array('gallery'=>$this->gallery_id, 'section'=>$this->section_id, 'foto'=>$this->id));
        }
        return $this->_url;
    }
}