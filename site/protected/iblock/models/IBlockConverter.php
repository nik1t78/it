<?php

class IBlockConverter extends CActiveRecord
{
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    public function getDbConnection()
    {
        return Yii::app()->db2;
    }
    
    public function tableName()
    {
        return 'art_site_novosti';
    }
    
    public function rules()
    {
        return array(
            array('id, razdel, zag, plain_html, telo, kogda, anons, vurl, srcid, ist, fname, onmain, tg, org, kogdab, kogdae, vremya, hrdlink', 'safe'),
        );
    }
}