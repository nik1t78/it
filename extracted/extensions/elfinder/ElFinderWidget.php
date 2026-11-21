<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ElFinderWidget
 *
 * @author root
 */
class ElFinderWidget extends CWidget
{
    public $options=array();
    public $htmlOptions=array();

    public function  run()
    {
        if(!isset($this->options['url']))
            throw new CException('Вы не указали ссылку к коннектору');

        $this->htmlOptions['id']=$this->getId();
        echo CHtml::tag('div',$this->htmlOptions);

        $a=CHtml::asset(dirname(__FILE__).'/assets/');
        $cs=Yii::app()->clientScript;

        $cs->registerCssFile('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/themes/smoothness/jquery-ui.css');
        //$cs->registerCssFile($a.'/css/elfinder.min.css');
        //$cs->registerCssFile($a.'/css/theme.css');
        //$cs->registerCssFile($a.'/js/ui-themes/base/ui.all.css');
        
        //$cs->registerCoreScript('jquery.ui');
        //$cs->registerScriptFile($a."/js/jquery-ui.min.js");
        $cs->registerScriptFile(Yii::app()->baseUrl."/js/jquery-migrate-1.1.1.min.js");
        //$cs->registerScriptFile($a.'/js/elfinder.min.js');
    $cs->registerScriptFile($a."/js/elFinder.js");
    $cs->registerScriptFile($a."/js/elFinder.version.js");
    $cs->registerScriptFile($a."/js/jquery.elfinder.js");
    $cs->registerScriptFile($a."/js/elFinder.resources.js");
    $cs->registerScriptFile($a."/js/elFinder.options.js");
    $cs->registerScriptFile($a."/js/elFinder.history.js");
    $cs->registerScriptFile($a."/js/elFinder.command.js");
    
    $cs->registerScriptFile($a."/js/ui/overlay.js");
    $cs->registerScriptFile($a."/js/ui/workzone.js");
    $cs->registerScriptFile($a."/js/ui/navbar.js");
    $cs->registerScriptFile($a."/js/ui/dialog.js");
    $cs->registerScriptFile($a."/js/ui/tree.js");
    $cs->registerScriptFile($a."/js/ui/cwd.js");
    $cs->registerScriptFile($a."/js/ui/toolbar.js");
    $cs->registerScriptFile($a."/js/ui/button.js");
    $cs->registerScriptFile($a."/js/ui/uploadButton.js");
    $cs->registerScriptFile($a."/js/ui/viewbutton.js");
    $cs->registerScriptFile($a."/js/ui/searchbutton.js");
    $cs->registerScriptFile($a."/js/ui/sortbutton.js");
    $cs->registerScriptFile($a."/js/ui/panel.js");
    $cs->registerScriptFile($a."/js/ui/contextmenu.js");
    $cs->registerScriptFile($a."/js/ui/path.js");
    $cs->registerScriptFile($a."/js/ui/stat.js");
    $cs->registerScriptFile($a."/js/ui/places.js");
    
    $cs->registerScriptFile($a."/js/commands/back.js");
    $cs->registerScriptFile($a."/js/commands/forward.js");
    $cs->registerScriptFile($a."/js/commands/reload.js");
    $cs->registerScriptFile($a."/js/commands/up.js");
    $cs->registerScriptFile($a."/js/commands/home.js");
    $cs->registerScriptFile($a."/js/commands/copy.js");
    $cs->registerScriptFile($a."/js/commands/cut.js");
    $cs->registerScriptFile($a."/js/commands/paste.js");
    $cs->registerScriptFile($a."/js/commands/open.js");
    $cs->registerScriptFile($a."/js/commands/rm.js");
    $cs->registerScriptFile($a."/js/commands/info.js");
    $cs->registerScriptFile($a."/js/commands/duplicate.js");
    $cs->registerScriptFile($a."/js/commands/rename.js");
    $cs->registerScriptFile($a."/js/commands/help.js");
    $cs->registerScriptFile($a."/js/commands/getfile.js");
    $cs->registerScriptFile($a."/js/commands/mkdir.js");
    $cs->registerScriptFile($a."/js/commands/upload.js");
    $cs->registerScriptFile($a."/js/commands/download.js");
    $cs->registerScriptFile($a."/js/commands/edit.js");
    $cs->registerScriptFile($a."/js/commands/quicklook.js");
    $cs->registerScriptFile($a."/js/commands/quicklook.plugins.js");
    $cs->registerScriptFile($a."/js/commands/extract.js");
    $cs->registerScriptFile($a."/js/commands/archive.js");
    $cs->registerScriptFile($a."/js/commands/search.js");
    $cs->registerScriptFile($a."/js/commands/view.js");
    $cs->registerScriptFile($a."/js/commands/resize.js");
    $cs->registerScriptFile($a."/js/commands/sort.js");
    $cs->registerScriptFile($a."/js/commands/netmount.js");
    $cs->registerScriptFile($a."/js/commands/pixlr.js");
    //$cs->registerScriptFile($a."/js/elfinder.min.js");
    $cs->registerScriptFile($a."/js/i18n/elfinder.ru.js");
    $cs->registerScriptFile(Yii::app()->theme->baseUrl."/js/plugins/elfinder/jquery.dialogelfinder.js");
    
    $cs->registerCssFile($a.'/css/common.css');
    $cs->registerCssFile($a.'/css/dialog.css');
    $cs->registerCssFile($a.'/css/toolbar.css');
    $cs->registerCssFile($a.'/css/navbar.css');
    $cs->registerCssFile($a.'/css/statusbar.css');
    $cs->registerCssFile($a.'/css/contextmenu.css');
    $cs->registerCssFile($a.'/css/cwd.css');
    $cs->registerCssFile($a.'/css/quicklook.css');
    $cs->registerCssFile($a.'/css/commands.css');
    $cs->registerCssFile($a.'/css/fonts.css');
    $cs->registerCssFile($a.'/css/theme.css');
        if(!empty($this->options['lang']))
            $cs->registerScriptFile($a.'/js/i18n/elfinder.'.$this->options['lang'].'.js');

        $cs->registerScript('el'.$this->getId(),'jQuery("#'.$this->getId().'").elfinder('.CJavaScript::encode($this->options).')',  CClientScript::POS_READY);

    }

    public function  __set($name, $value)
    {
        if(!$this->canSetProperty($name))
            $this->options[$name]=$value;
        else parent::__set($name, $value);
    }

    public function __get($name)
    {
        if(!$this->canGetProperty($name))
            return $this->options[$name];
        else return parent::__get($name);
    }

    //put your code here
}
