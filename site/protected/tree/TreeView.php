<?php

class TreeView extends TreeBehavior{

    /**
     * Формирование массива из дерева разделов
     **/
    public function getTreeViewData($returnrootnode = true, $keyfield = null)
    {
        if($keyfield == null)
        { // Поле, по которому идет идентификация
            $keyfield = 'id';
        }
        // Получаем дерево из БД
        $criteria = new CDbCriteria();
        $criteria->order = 'lft';
        //$criteria->addNotInCondition('id', array(1));
        $rawtree = $this->owner->findAll($criteria);
        // Init variables needed for the array conversion
        $tree = array();
        $node =& $tree;
        $position = array();
        $lastitem = 0;
        $depth = $this->getLevelValue();
        
        foreach($rawtree as $rawitem)
        {
            // If its a deeper item, then make it subitems of the current item
            if ($rawitem->getLevelValue() > $depth)
            {
                $position[] =& $node; //$lastitem;
                $depth = $rawitem->getLevelValue();
                $node =& $node[$lastitem]['children'];
            }
            // If its less deep item, then return to a level up
            else
            {
                while ($rawitem->getLevelValue() < $depth)
                {
                    end($position);
                    $node =& $position[key($position)];
                    array_pop($position);
                    $depth = $node[key($node)]['node']->getLevelValue();
                }
            }
            /**
            if(!$rawitem->hasChildNodes())
                $rawitem->owner->name = '<a href="/category/'.$rawitem->owner->id.'">'.$rawitem->owner->name.'</a>';
            else
                $rawitem->owner->name = '<span>'.$rawitem->owner->name.'</span>';
            **/
            // Add the item to the final array
            $node[$rawitem->$keyfield]['node'] = $rawitem;
            $node[$rawitem->$keyfield]['id'] = 'node_'.$rawitem->owner->id;
            $node[$rawitem->$keyfield]['text'] = $rawitem->owner->name;
            //$node[$rawitem->$keyfield]['expanded'] = true; //ветка будет развернута по умолчанию
            // save the last items' name
            $lastitem = $rawitem->$keyfield;
        }
        // we don't care about the root node
        if (!$returnrootnode)
        {
            reset($tree);
            $tree = $tree[key($tree)]['children'];
            //array_shift($tree);
        }
        //VarDumper::dump($tree);
        return $tree;
    }
/**
    public function run(){
        $criteria = new CDbCriteria();
        $criteria->order = 'lft';
        $criteria->addNotInCondition('id', array(1));
        $cats = Category::model()->findAll($criteria);
        
        $level = -1;
        while(!empty($cats)){
            $cat = array_shift($cats);
            
            if($cat->level > $level){
                echo CHtml::openTag('ol', array('class'=>'categories'))."\n";
            }else if($cat->level < $level){
                echo str_repeat(CHtml::closeTag('ol')."\n", $level - $cat->level);
            }
            
            echo CHtml::openTag('li')."\n";
            
            //!!!
            echo CHtml::link(CHtml::encode($cat->title), array('//soft/category', 'id' => $cat->id));
            
            echo CHtml::closeTag('li')."\n";
            
            $level = $cat->level;
            
            if(empty($cats)){
                echo str_repeat(CHtml::closeTag("ol")."\n", $level+1);
            }
        }
    } **/
}