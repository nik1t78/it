<?php

class seoWidget extends CWidget
{
    public $module = 'settings';
    public $fields = array('meta_keywords', 'meta_description');

    public function run()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 'setting_id=(SELECT id FROM {{settings}} s WHERE s.name=:module)';
        $criteria->params = array(
            ':module' => $this->module
        );
        $criteria->addInCondition('t.name', $this->fields);
        
        $model = SettingsParams::model()->findAll($criteria);

        $cs = Yii::app()->clientScript;

        foreach ($model as $param) {
            $cs->registerMetaTag($param->value, str_replace('meta_', '',$param->name));
        }
    }
}

