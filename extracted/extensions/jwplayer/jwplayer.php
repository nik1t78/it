<?php

class jwplayer extends CWidget
{
    public $version = 'flash';
    public $containerId = 'videoPlayer';
    public $file;
    public $image;
    
    public function init()
    {        
        $baseDir = dirname(__FILE__);
        $assets = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'assets');

        $cs = Yii::app()->getClientScript();
        if ($this->version == 'flash')
            $cs->registerScriptFile($assets.'/jwplayer.js');
        elseif ($this->version == 'html5')
            $cs->registerScriptFile($assets.'/jwplayer.html5.js');        
    }
    
    public function run()
    {
        echo '<div id="'.$this->containerId.'">Loading the player...</div>';
        echo '<script type="text/javascript">jwplayer("'.$this->containerId.'").setup({
            file: "'.$this->file.'",
            image: "'.$this->image.'"
           });</script>';
    }
}
