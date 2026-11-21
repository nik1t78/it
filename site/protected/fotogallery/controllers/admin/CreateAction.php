<?php

class CreateAction extends CAction{
    
    public function run($type, $gallery=0, $section=0){
        //$type_name = ucfirst(strtolower($type));
        switch ($type){
            case 'gallery':
                $this->createGallery();
                break;
            case 'section':
                $this->createSection($gallery, $section);
                break;
            case 'foto':
                $this->createFoto($gallery, $section);
                break;
            default:
                throw new CHttpException(500, Yii::t('system', 'Ошибка! Указан неверный параметр'));
                break;
        }  
    }
    
    /** Create new Infoblock */
    protected function createGallery(){
        $model = new FotoGallery;
        
        if(isset($_POST['FotoGallery'])){
            $created_date = date('Y-m-d H:i:s', strtotime($_POST['FotoGallery']['created_date']['date'].' '.$_POST['FotoGallery']['created_date']['time']));
            $_POST['FotoGallery']['created_date'] = $created_date;
            
            $model->attributes = $_POST['FotoGallery'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('foto', 'Фотогалерея "'.$model->title.'" успешно создана.'));
                    $this->controller->redirect(array('/fotogallery/admin/index'));
                }
                
            }
        }
        
        $this->controller->render('createGallery', array('model'=>$model));
    }
    
    /* Create new section of infoblock */
    protected  function createSection($gallery_id, $section_id){
        $model = new FotoSection;
        
        if(isset($_POST['FotoSection'])){
            $model->attributes = $_POST['FotoSection'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('foto', 'Раздел "'.$model->title.'" успешно добавлен.'));
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        
        
        if($gallery_id != 0){        
            $gallery = FotoGallery::model()->findByPk($gallery_id);
            if($gallery == null) 
                throw new CHttpException(404, Yii::t('foto', 'Галерея с id: '.$gallery_id.' не найдена.'));
            $model->gallery_id = $gallery_id;
        }
        
        if($section_id != 0){
            $section = FotoSection::model()->findByPk($section_id, 'gallery_id=:gallery_id', array(':gallery_id'=>$gallery_id));
            if($section == null)
                throw new CHttpException(404, Yii::t('foto', 'Раздел с id: '.$section_id.' в галерее "'.$gallery->title.'" не найден.'));
            $model->section_id = $section_id;
        }
        
        $this->controller->render('createSection', array('model'=>$model, 'gallery'=>$gallery));
    }
    
    /* Create new foto of infoblock */
    protected function createFoto($gallery_id, $section_id){
        $model = new Foto;
        
        if (isset($_POST['Foto'])){
            // Правим путь к файлу
            $_POST['Foto']['image'] = Misc::normalizeFilePath($_POST['Foto']['image']);  
            $tmp = explode('/', $_POST['Foto']['image']);
            $original_name = array_pop($tmp);
            $original_path = implode('/', $tmp).'/';
            $absolute_path = Yii::getPathOfAlias('webroot').$original_path;
            //$_POST['Foto']['image'] = $original_path . $original_name;
            
            /** 
             * Если активен флажок "Сделать превью", получаем необходимые параметры и делаем превью
             */
            if (isset($_POST['Foto']['createPreview'])){                
                $_POST['Foto']['preview'] = $model->createPreview($absolute_path.$original_name, $_POST['Foto']['preview_prefix'], $_POST['Foto']['preview_width'], $_POST['Foto']['preview_height'], $_POST['Foto']['preview_crop']);
            }
            
            if(isset($_POST['Foto']['width']) || isset($_POST['Foto']['height'])){
                // если указаны размеры для основного изображения - меняем их
                $new_width = isset($_POST['Foto']['width']) ? $_POST['Foto']['width'] : false;
                $new_height = isset($_POST['Foto']['height']) ? $_POST['Foto']['height'] : false;
                
                $image = Yii::app()->image->open($absolute_path.$original_name);
                $original_size = $image->getSize();
                
                // соотношение сторон. Рассчет соотношения, насколько нужно изменить ширину и высоту
                $ratio_x = $new_width ? 1 / ($original_size->getWidth() / $new_width) : 1;
                $ratio_y = $new_height ? 1 / ($original_size->getHeight() / $new_height) : 1;
                
                $new_size = $image->getSize()->scale($ratio_x < $ratio_y ? $ratio_x : $ratio_y);
                
                $finall_image = $image->resize($new_size)->save($absolute_path.$original_name);
            }
            
            $model->attributes = $_POST['Foto'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('foto', 'Фото успешно добавлено.'));
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        if($gallery_id != 0){        
            $gallery = FotoGallery::model()->findByPk($gallery_id);
            if($gallery == null) 
                throw new CHttpException(404, Yii::t('foto', 'Галерея с id: '.$gallery_id.' не найдена.'));
            $model->gallery_id = $gallery_id;
        }
        
        if($section_id != 0){
            $section = FotoSection::model()->findByPk($section_id, 'gallery_id=:gallery_id', array(':gallery_id'=>$gallery_id));
            if($section == null)
                throw new CHttpException(404, Yii::t('foto', 'Раздел с id: '.$section_id.' в галерее "'.$gallery->title.'" не найден.'));
            $model->section_id = $section_id;
        }
                        
        $this->controller->render('createFoto', array('model'=>$model, 'gallery'=>isset($gallery)?$gallery:0, 'section'=>isset($section)?$section:0));
    }
}
