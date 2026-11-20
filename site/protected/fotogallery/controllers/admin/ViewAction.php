<?php

class ViewAction extends CAction{
    
    public function run($id, $type){        
        Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
        switch ($type){
            case 'gallery':
                $this->viewGallery($id);
                break;
            case 'section':
                $this->viewSection($id);
                break;
            case 'foto':
                $this->viewFoto($id);
                break;
            default:
                echo 'ошибка! неверный параметр';
                break;
        }  
               
        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);
        
    }
    
    
    /** View Gallery */
    protected function viewGallery($id){
        $gallery = FotoGallery::model()->findByPk($id);
        $criteria = new CDbCriteria();
        $criteria->condition = 'gallery_id=:gallery_id and section_id=0';
        $criteria->params = array(':gallery_id'=>$id);
        $criteria->order = 'sort ASC';
        $sections = FotoSection::model()->findAll($criteria);
        $elements = Foto::model()->findAll($criteria);
        
        $this->controller->render('viewGallery', array('gallery'=>$gallery, 'sections'=>$sections, 'elements'=>$elements));
    }
    
    /* View section of gallery */
    protected  function viewSection($id){
        $section = FotoSection::model()->findByPk($id);      
        $gallery = FotoGallery::model()->findByPk($section->gallery_id);  
        $criteria = new CDbCriteria();
        $criteria->condition = 'gallery_id=:gallery_id and section_id=:section_id';
        $criteria->params = array(
            ':gallery_id'=>$gallery->id,
            ':section_id'=>$id,
        );
        $criteria->order = 'sort ASC';
        
        $subSections = FotoSection::model()->findAll($criteria);
        $elements = Foto::model()->findAll($criteria);
        
        $this->controller->render('viewSection', array('gallery'=>$gallery, 'section'=>$section, 'subSections'=>$subSections, 'elements'=>$elements));
    }
    
    /* View element of infoblock */
    protected function viewElement($id){
        $model = Foto::model()->findByPk($id);
                
        $this->controller->render('viewElement', array('model'=>$model));
    }
    
}