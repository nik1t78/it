<?php

class FotoresizeController extends Controller
{
    public function actions()
    {
        return array(
            'resized' => array(
                'class' => 'ext.resizer.ResizerAction',
                'options' => array(
                    //Tmp dir to store cached resized images
                    'cache_dir' => Yii::getPathOfAlias('webroot').'/assets/',
                    //Web root dir to search images from
                    'base_dir' => Yii::getPathOfAlias('webroot').'/'
                )
            ),
        );
    }
}
