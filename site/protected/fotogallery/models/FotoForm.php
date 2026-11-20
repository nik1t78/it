<?php


class FotoForm extends CFormModel
{
    public $image;
    public $user_id;
    public $width;
    public $height;
    public $alt;
    public $url;
    public $type;
    
    public function rules()
    {
        return array(
            array('user_id, width, height, alt, url', 'required'),
            array('user_id, width, height', 'numerical', 'integerOnly'=>true),
            array('alt', 'length', 'max'=>250),
            array('url', 'length', 'max'=>150),
            array('url', 'filter', 'filter'=>array("TranslitFilter", "translitUrl")),
            array('url', 'unique', 'attributeName'=>'url', 'className'=>'Photo', 'message'=>'такой url уже занят'),
            //array('image', 'file', 'types'=>'jpg'),
        );
    }
    
    public function attributeLabels()
    {
        return array(
            'url'=>'Уникальный url фотографии',
            'alt'=>'Описание фотографии',
            'image'=>'Изображение',
        );
    }
    
    /**
     * Функция загружает картинку, подгоняет размер, сохраняет информацию в БД, затем на жесткий диск
     * 
     * входящие параметры:
     * картинка
     * тип картинки
     * 
     * @param file $image картинка
     * @param string $type тип картинки, доступные типы картинки можно определить Photo::infIMG
     * @return bool сохранена или нет
     */
    public function savePhoto($image, $type="full_img")
    {
        //Загрузка компонента работы с изображениями
        Yii::app()->setComponents(array(
            'imagemod' => array(
                'class' => 'application.extensions.imagemodifier.CImageModifier',
            ),
        ));
        //общие настройки для загружаемого изображения
        $img = Yii::app()->imagemod->load($image);
        
        //определение конечной высоты и ширины
        $img->image_resize = TRUE;
        $img->image_ratio_y = true;
        $this->width = $img->image_x = Photo::infIMG($type, 0);
        $this->height = round(($img->image_src_y * $img->image_x) / $img->image_src_x);
        $this->type = $type;
        
        //база данных
        $model = new Photo();
        $model->type = $this->type;
        $model->user_id = $this->user_id;
        $model->width = $this->width;
        $model->height = $this->height;
        $model->alt = $this->alt;
        $model->url = Photo::infIMG($this->type, 'prif').$this->url;
        $model->key_photo = $this->url;
        $model->created = new CDbExpression("NOW()");
        
        if($model->validate() && $this->validate()){
            //Если сохранена в БД, сохраняется и на жесткий диск
            if($model->save()){
                $img->file_new_name_body = $model->url;
                if($img->process(Yii::app()->getBasePath()."/../images"))
                    return true;
                else
                    return false;
            } else 
                return false;
        } else {
            $this->addErrors($model->getErrors());
            return false;
        }
    }
}
?>
