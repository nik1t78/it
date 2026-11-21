<?php

class ListAction extends CAction{
    private $iblock_id;
    
    public function run($iblock_id, $section_id = 0){
        $this->iblock_id = $iblock_id;
        $iblock = $this->controller->loadModel($iblock_id);
        if($section_id > 0){
            $section = IblockSection::model()->findByPk($section_id);
        }
        $sections = IblockSection::model()->findAll('iblock_id=:iblock_id and iblock_section_id=:section_id', array(':iblock_id'=>$iblock_id, ':section_id'=>$section_id));
        $elements = IblockElement::model()->findAll('iblock_id=:iblock_id and iblock_section_id=:section_id', array(':iblock_id'=>$iblock_id, ':section_id'=>$section_id));
        
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