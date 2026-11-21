<?php

class ViewAction extends CAction{
    
    public function run($module){
        // Получаем из параметров имя модуля, пытаемся его найти
        $moduleObj = Yii::app()->getModule($module);
        if(!$moduleObj)
            throw new CHttpException(404, Yii::t('settings', 'Запрашиваемый модуль не найден.'));
        
        //Если модуль найден - получаем его редактируемые параметры
        $editableParams = $moduleObj->getEditableParams();
        
        //Если параметров нет - выводим сообщение и завершаем выполнение приложения
        if(count($editableParams) <= 0){
            $this->controller->render('error', array('errors' => array(Yii::t('settings', 'Для данного модуля настройки не предусмотрены.'))));
            Yii::app()->end();
        }
        
        //Ищем в БД группу параметров, соответствующую запрашиваемуму модулю
        $criteria = new CDbCriteria;
        $criteria->condition = 't.name=:module';
        $criteria->params = array(':module' => $module);
        $criteria->with = array('params' => array('scopes' => array('order_by_sort')));
        
        $model = Settings::model()->find($criteria);
        if($model === null)
            throw new CHttpException(404, Yii::t('settings', 'В БД не найдены настройки для запрошенного модуля. Возможно модуль был неправильно установлен.'));
        
        $params = $model->params;
        if($params === null)
            throw new CHttpException(404, Yii::t('settings', 'В БД не найдены настройки для запрошенного модуля. Возможно модуль был неправильно установлен.'));
        
        $elements = array();
        foreach($params as $param){
            $label = CHtml::label($param->title, $param->name);
            switch($param->code){ 
                case ('radio'):
                    $value = $param->value;
                    settype($value, 'boolean');
                    $field = CHtml::radioButton('SettingsParams['.$param->name.']',$value); break;
                case ('checkbox'):
                    $value = $param->value;
                    settype($value, 'boolean');
                    $field = CHtml::checkBox('SettingsParams['.$param->name.']', $value); break;
                case ('textarea'):
                    $field = CHtml::textArea('SettingsParams['.$param->name.']', $param->value); break;                   
                case ('text'): 
                default:
                    $field = CHtml::textField('SettingsParams['.$param->name.']', $param->value,array('size'=>60,'maxlength'=>255)); 
            }
            array_push($elements, array('label' => $label, 'field' => $field));
        }
        
        
        
        
        
        
        /**
        $criteria = new CDbCriteria;
        $criteria->condition = 't.name=:module';
        $criteria->params = array(':module'=>$module);
        $criteria->with = array('params' => array('scopes' => array('order_by_sort')));
        $model=Settings::model()->find($criteria);
	
       
        if($model===null)
            throw new CHttpException(404, Yii::t('settings', 'Запрошенные настройки не найдены'));
        
        $params = $model->params; 
        **/
        
        /*** из yupe 
        $module = Yii::app()->getModule($module);
        
        if(!$module)
            throw new CHttpException(404, Yii::t('settings', 'Страница настроек данного модуля недоступна'));
        
        $elements = array();
        $editableParams = $module->getEditableParams();
        $moduleParamsLabels = $module->getParamsLabels();
        VarDumper::dump($module);**/
        /**
        foreach($module as $key => $value){
            if(array_key_exists($key, $editableParams))
                    $elements[$key] = CHtml::label($moduleParamsLabels[$key], $key).CHtml::dropDownList ($key, $value, $editableParams[$key]);
            
            if(in_array($key, $editableParams))
                    $elements[$key] = CHtml::label($moduleParamsLabels[$key], $key).CHtml::textField($key, $value, array('maxlength' => 200));
        }
        **/
        //сформировать боковое меню из ссылок на настройки модулей
  /**      $modules = Yii::app()->getModule('backend')->getModules();
        $this->controller->menu = array();
        
        foreach($modules as $oneModule){
            if($oneModule->getEditableParams()){
                array_push($this->menu, array('label'=>$oneModule->getName(), 'url'=>$this->createUrl('/backend/settings/', array('module'=>$oneModule->getId()))));
            }
        } **/
        /****** ****/
        $this->controller->render('view', array('model'=>$model, 'elements'=>$elements));
    }
}