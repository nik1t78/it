<?php

/**
 * Created by PhpStorm.
 * User: covax
 * Date: 14.09.16
 * Time: 9:33
 */
class OldTableContent extends CActiveRecord {
    public static function model($className=__CLASS__){
        return parent::model($className);
    }

    public function getDbConnection() {
        return Yii::app()->db2;
    }

    public function tableName() {
        return 'protectionnode_revisions';
    }

    public function rules() {
        return array(
            array('nid, vid, uid, title, body, teaser, timestamp', 'safe')
        );
    }
}
