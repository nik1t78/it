<?php

class TreeBehavior extends NestedSetBehavior{
    /**
     *  Get the value stored in the "lft" column for this node.
     *  This value is usually null when the node is not yet stored using the append* or insert* functions.
     *  @return The left value for this node, or null when unknown.
     */
    public function getLeftValue(){
        return $this->owner->attributes[$this->leftAttribute];
    }
    
    /**
     *  Get the value stored in the "rgt" column for this node. 
     *  This value is usually null when the node is not yet stored using the append* or insert* functions.
     *  @return The right value for this node, or null when unknown.
     */
    public function getRightValue()
    {
        return $this->owner->attributes[$this->rightAttribute];
    }
    
    /**
     *  Get the value stored in the "level" column for this node.
     *  This value is usually null when the node is not yet stored using the append* or insert* functions.
     *  @return The level value for this node, or null when unknown.
     */
    protected function getLevelValue()
    {
        return $this->owner->attributes[$this->levelAttribute];
    }
        
    /**
     * Returns false when the node is a leaf node (i.e. has no child nodes)
     * @return boolean True when the node has childe nodes, false when the node is a leaf.
     */
    public function hasChildNodes()
    {
        return $this->getLeftValue() != ($this->getRightValue() - 1);
    }
}