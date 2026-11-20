<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once(dirname(__FILE__).'/elFinderConnector.class.php');
require_once(dirname(__FILE__).'/elFinder.class.php');
require_once(dirname(__FILE__).'/elFinderVolumeDriver.class.php');
require_once(dirname(__FILE__).'/elFinderVolumeLocalFileSystem.class.php');
/**
 * Description of ElrteAction
 *
 * @author root
 */
class ElFinderAction extends CAction
{
    public $options=array();
   
    public function  run()
    {/**
        $this->options=array_merge($this->options,array(
            'root'=>Yii::getPathOfAlias('webroot.upload'),
            'URL'=>Yii::app()->baseUrl.'/upload/',
            'rootAlias'=>'Home',
        ));**/
        $opts = array(
            // 'debug' => true,
            'roots' => array(
                array(
                    'driver'        => 'LocalFileSystem',   // driver for accessing file system (REQUIRED)
                    'path'          => Yii::getPathOfAlias('webroot.upload'),         // path to files (REQUIRED)
                    'URL'           => Yii::app()->baseUrl.'/upload/', // URL to files (REQUIRED)
                    'accessControl' => 'access'             // disable and hide dot starting files (OPTIONAL)
                )
            )
        );
        $opts = array_merge($this->options, $opts);

        //$fm = new elFinder($this->options);
        //$fm->run();
        $connector = new elFinderConnector(new elFinder($opts));
        $connector->run();
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
}
