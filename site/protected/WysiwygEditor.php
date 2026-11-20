<?php

class WysiwygEditor extends CWidget{
	public $htmlOptions = array();
	public $model;
	public $attribute;

	public function init(){
		$text = CHtml::resolveValue($this->model, $this->attribute);
		CHtml::resolveNameID($this->model, $this->attribute, $this->htmlOptions); 
		
		
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile(Yii::app()->theme->baseUrl."/js/ckeditor/ckeditor.js", CClientScript::POS_HEAD);
		$cs->registerScript('wysiwygEnable', '
			CKEDITOR.replace("'.$this->htmlOptions['id'].'",
			{
				uiColor		: "#e3e3e3",
				toolbar		: "PageEditorAdmin",
				filebrowserBrowseUrl : "/backend/filemanager/browser/",
				filebrowserImageBrowseUrl : "/backend/filemanager/browser/?Type=Images"
			});
		');
		
				
		$this->render('wysiwygEditor', array(
			'htmlOptions' => $this->htmlOptions,
			'text' => $text,
		));
	}
	
	public function run(){
	
	}
    
}