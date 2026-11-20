<?php

class OldTable extends CActiveRecord
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
        return 'protectionnode';
    }

    public function rules()
    {
        return array(
            array('nid, vid, type, title, uid, created, changed, status', 'safe'),
        );
    }

    public function relations() {
        return array(
            'content' => array(self::BELONGS_TO, 'OldTableContent', 'vid'),
        );
    }
}
