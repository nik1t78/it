<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

Yii::import('zii.widgets.CBreadcrumbs');

class BackendBreadcrumbs extends CBreadcrumbs{
    
    public $tagName='ul';
    
    public function run(){
        if(empty($this->links))
            return;

	echo CHtml::openTag($this->tagName,$this->htmlOptions)."\n";
	$links=array();
	if($this->homeLink===null)
            echo '<li>'.CHtml::link(Yii::t('zii','Home'),Yii::app()->homeUrl).'</li>';
	else if($this->homeLink!==false)
            echo '<li>'.$this->homeLink.'</li>';
	foreach($this->links as $label=>$url)
	{
            if(is_string($label) || is_array($url)){
                echo CHtml::openTag('li')."\n";
                echo CHtml::link($this->encodeLabel ? CHtml::encode($label) : $label, $url);
            }
            else{
                echo CHtml::openTag('li', array('class'=>'current'))."\n";
                echo CHtml::link($this->encodeLabel ? CHtml::encode($url) : $url, '');
            }
            echo CHtml::closeTag('li')."\n";
	}
	echo CHtml::closeTag($this->tagName);
        echo '<div class="clear"></div>';
    }
}
?>
