<?php

class UpdateAction extends CAction{
    
    public function run($id, $type){
        switch ($type){
            case 'gallery':
                $this->updateGallery($id);
                break;
            case 'section':
                $this->updateSection($id);
                break;
            case 'foto':
                $this->updateFoto($id);
                break;
            default:
                echo 'ошибка! неверный параметр';
                break;
        }  
               
        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);
        
    }
    
    
    /** Update Gallery */
    protected function updateGallery($id){
        $model = FotoGallery::model()->findByPk($id);
        
        if(isset($_POST['FotoGallery'])){            
            $created_date = date('Y-m-d H:i:s', strtotime($_POST['FotoGallery']['created_date']['date'].' '.$_POST['FotoGallery']['created_date']['time']));
            $_POST['FotoGallery']['created_date'] = $created_date;
            
            $model->attributes = $_POST['FotoGallery'];
            
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('foto', 'Все изменения сохранены.'));
                    $this->controller->redirect(array('/fotogallery/admin/index'));
                }
                
            }
        }
        
        $this->controller->render('updateGallery', array('model'=>$model));
    }
    
    /* Update section of gallery */
    protected  function updateSection($id){
        $model = FotoSection::model()->findByPk($id);
        $gallery = FotoGallery::model()->findByPk($model->gallery_id);
        
        if(isset($_POST['FotoSection'])){
            $model->attributes = $_POST['FotoSection'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('foto', 'Все имзенения сохранены.'));
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        
        $this->controller->render('updateSection', array('model'=>$model, 'gallery'=>$gallery));
    }
    
    /* Update foto of gallery */
    protected function updateFoto($id){
        $model = Foto::model()->findByPk($id);
        $gallery = FotoGallery::model()->findByPk($model->gallery_id);
        $section = FotoSection::model()->findByPk($model->section_id);
                
        if(isset($_POST['Foto'])){
            
            //VarDumper::dump($_POST['Foto']);
            
            // Правим путь к файлу
            $_POST['Foto']['image'] = Misc::normalizeFilePath($_POST['Foto']['image']);            
            $tmp = explode('/', $_POST['Foto']['image']);
            $original_name = array_pop($tmp);
            $original_path = implode('/', $tmp).'/';
            $absolute_path = Yii::getPathOfAlias('webroot').$original_path;
            
            if(!file_exists($absolute_path.$original_name)){   
                throw new CHttpException(400, Yii::t('foto', 'Файл изображения не найден или имеет недопустимое имя.'));
            }
            
            /** 
             * Если активен флажок "Сделать превью", получаем необходимые параметры и делаем превью
             */
            if (isset($_POST['Foto']['createPreview'])){ 
                $preview = $model->createPreview($absolute_path.$original_name, $_POST['Foto']['preview_prefix'], $_POST['Foto']['preview_width'], $_POST['Foto']['preview_height'], $_POST['Foto']['preview_crop']);
                $_POST['Foto']['preview'] = $preview ? $preview : null;
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
            
            //VarDumper::dump($_POST['Foto']);
            $model->attributes = $_POST['Foto'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('foto', 'Все изменения сохранены.'));
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        
        /**
         * Указываем размеры существующего изображения
         */
        if(!empty($model->image) && file_exists(Yii::getPathOfAlias('webroot').$model->image)) {            
            $image = Yii::app()->image->open(Yii::getPathOfAlias('webroot').$model->image);
            $image_size = $image->getSize();
        
            if($model->width == 0)
                $model->width = $image_size->getWidth();
            if($model->height == 0)
                $model->height = $image_size->getHeight();
        }
        /*
         * Указываем размеры, префикс превьюшки
         */
        if(!empty($model->preview) && file_exists(Yii::getPathOfAlias('webroot').$model->preview)){
            $tmp_preview = explode('/', $model->preview);
            $preview_prefix = preg_replace('/_([A-Za-z0-9-_]+).jpg$/', '', array_pop($tmp_preview));
            
            $preview = Yii::app()->image->open(Yii::getPathOfAlias('webroot').$model->preview);
            $preview_size = $preview->getSize();
            $model->preview_width = $preview_size->getWidth();
            $model->preview_height = $preview_size->getHeight();
            $model->preview_prefix = $preview_prefix.'_';
        }
        
        $this->controller->render('updateFoto', array('model'=>$model, 'gallery'=>$gallery, 'section'=>isset($section)?$section:0));
    }
    
}
