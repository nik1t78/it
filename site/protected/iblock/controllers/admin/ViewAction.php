<?php

class ViewAction extends CAction{
    
    public function run($id, $type){        
        Yii::app()->user->returnUrl = Yii::app()->request->requestUri;
        switch ($type){
            case 'iblock':
                $this->viewIblock($id);
                break;
            case 'section':
                $this->viewSection($id);
                break;
            case 'element':
                $this->viewElement($id);
                break;
            default:
                echo 'ошибка! неверный параметр';
                break;
        }  
               
        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);
        
    }
    
    
    /** View Infoblock */
    protected function viewIblock($id){
        $iblock = IBlock::model()->findByPk($id);
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and iblock_section_id=0';
        $criteria->params = array(':iblock_id'=>$id);
        $criteria->order = 'sort, timestamp_x DESC';
        $sections = IBlockSection::model()->findAll($criteria);
                
                $elements_count = IBlockElement::model()->count($criteria);
                $pages = new CPagination($elements_count);
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
                
        $elements = IBlockElement::model()->findAll($criteria);
        
        $this->controller->render('viewIblock', array('iblock'=>$iblock, 'sections'=>$sections, 'elements'=>$elements, 'pages'=>$pages));
    }
    
    /* View section of infoblock */
    protected  function viewSection($id){
        $section = IBlockSection::model()->findByPk($id);      
        $iblock = IBlock::model()->findByPk($section->iblock_id);  
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and iblock_section_id=:section_id';
        $criteria->params = array(
            ':iblock_id'=>$iblock->id,
            ':section_id'=>$id,
        );
        $criteria->order = 'sort, timestamp_x DESC';
        
        $subSections = IBlockSection::model()->findAll($criteria);
                
                $elements_count = IBlockElement::model()->count($criteria);
                $pages = new CPagination($elements_count);
                $pages->pageSize = 10;
                $pages->applyLimit($criteria);
        
        $elements = IBlockElement::model()->findAll($criteria);
        
        $this->controller->render('viewSection', array('iblock'=>$iblock, 'section'=>$section, 'subSections'=>$subSections, 'elements'=>$elements, 'pages'=>$pages));
    }
    
    /* View element of infoblock */
    protected function viewElement($id){
        $model = IBlockElement::model()->findByPk($id);
                
        $this->controller->render('viewElement', array('model'=>$model));
    }
    
}