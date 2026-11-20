<?php

class ListAction extends CAction{
    private $iblock_id;
    
    public function run($iblock_id, $section_id = 0){
        $this->iblock_id = $iblock_id;
        $iblock = $this->controller->loadModel($iblock_id);
        if($section_id > 0){
            $section = IblockSection::model()->findByPk($section_id);
        }
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and iblock_section_id=:section_id';
        $criteria->params = array(
            ':iblock_id'=>$iblock_id, 
            ':section_id'=>$section_id
        );
        $criteria->order = 'timestamp_x ASC';
        $sections = IblockSection::model()->findAll($criteria);
        $elements = IblockElement::model()->findAll($criteria);
        
        if($section_id == 0){
            $this->controller->breadcrumbs = array(
                $iblock->name
            );
        }else{            
            $this->controller->breadcrumbs = array_merge(array(
                $iblock->name => Yii::app()->createUrl('/iblock/admin/list', array('iblock_id'=>$iblock->id)),                
            ), $this->getBreadcrumbs($section_id));            
        }
        
        $this->controller->render('list', array('iblock'=>$iblock, 'sections'=>$sections, 'elements'=>$elements));
    }
    
    public function getBreadcrumbs($section_id){
        $parents = IblockSection::getParents($section_id);
        
        $result = array();
        $i = 1;
        $count = count($parents);
        foreach($parents as $name => $id){
            if($i == $count){
                $result[] = $name;
            }else{
                $result[$name] = Yii::app()->createUrl('/iblock/admin/list', array('iblock_id'=>$this->iblock_id, 'section_id'=>$id));
            }
            $i++;
        }
        
        return $result;
    }
}