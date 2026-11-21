<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class ContragentUpload extends CFormModel
{
	public $import;
        

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('import', 'file', 'types'=>'csv'),
			// verifyCode needs to be entered correctly
			//array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
                        'import'=>'Файл выгрузки'
		);
	}
        
        
        public function parseFirstString($filePath){
            $fp = fopen($filePath, 'r');
            /*            
            $string = fgets($fp);
            //$string = iconv('CP1251', 'UTF-8', $string);
            $stringArr = explode(';', $string);*/
            $stringArr = $this->getFirstString($fp);
             
            fclose($fp);
            return $stringArr;
        }
        
        private function getFirstString($fp){
            $string = fgets($fp);
            $string = iconv('CP1251', 'UTF-8', $string);
            $stringArr = explode(';', $string);
            $i = 0;
            foreach($stringArr as $field){
                if(empty($field)) 
                    $i --;
                else
                    $i ++;
            }
            if($i <= 0) 
                return $this->getFirstString ($fp);
            else{
                return $stringArr;
            }
        }
        
        public function parseFile($filePath, $paramsArr){
            if(!file_exists($filePath)){
                throw new CHttpException(404, Yii::t('user', 'Файл выгрузки не обнаружен.'));
            }
            $models = array();
            $response = array(
                'errors' => array(
                    'count' => 0,
                    'items' => array()
                ),
                'warnings' => array(
                    'count' => 0,
                    'items' => array()
                ),
                'create' => array(
                    'count' => 0,
                    'items' => array()
                ),
                'update' => array(
                    'count' => 0,
                    'items' => array()
                ),
                'delete' => array(
                    'count' => 0,
                    'items' => array()
                ),
            );
            
            $fp = fopen($filePath, 'r');
            //$firstLine = fgets($fp); //считываем первую строку, чтобы она не участвовала в цикле
            $firstLine = $this->getFirstString($fp);
            
            $keyParam = 'tin';
            //$keyParam = Yii::app()->request->getPost('CatalogUploadKey'); //параметр, по которому запись ищется в БД
            //Пока не даем менять этот параметр, т.к. юзер может указать как ключ параметр, который не является уникальным. Например, parent_id. Пока что проверяем наличие параметров id и name и по ним ищем запись.
            
            //$arrKeysSectionName = array_merge(array_keys($paramsArr, 'name'), array_keys($paramsArr, 'section__name')); //массив индексами полей, в которых содержатся названия разделов
            
            while(($line = fgets($fp)) !== false){   
                //$level = 1; //по умолчанию уровень вложенности
                //$parentId = 0; //родитель по умолчанию
                
                $line = iconv('CP1251', 'UTF-8', $line);
                $lineArr = explode(';', $line);
                if(empty($lineArr) || empty($lineArr[0])) continue; //пропускаем пустые строки и начинающиеся с пустого значения.
                /**
                 * Обходим массив $lineArr, по порядковому номеру определяем название параметра, которому нужно присвоить значение.
                 * Сначала определяем является запись в строке разделом или товаром. Затем в соотв. модели ищем запись с указанным id, если она найдена, заполняем ее данными
                 * из строки. Если не найдена - создаем новую запись с соотв. параметрами.
                 */
                
                
                //ищем запись в БД по ключевому параметру
                if(!isset($keyParam)){ //если ключевой параметр - индекс не задан, ищем в параметрах ключ id или section__id, element__id и назначаем это поле как индекс
                    if(($paramIndex = array_search('id', $paramsArr)) !== false){
                        $uniqueKey = $paramIndex;
                    }elseif(($paramIndex = array_keys($paramsArr, 'name')) !== false){     
                        // если ключ с  id не найден, ищем ключ с name, section__name, element__name и назначаем его индексом
                        foreach($paramIndex as $index){
                            if(strlen(trim($lineArr[$index])))
                                $uniqueKey = $index;
                        }
                    }
                }else{ //если ключевой параметр задан - назначаем его индексом
                    $paramIndex = array_keys($paramsArr, $keyParam);
                    $uniqueKey = $paramIndex[0];
                }
                
                /*
                //ищем в параметрах поле code, section__code, element__code
                if($paramIndex = array_search('code', $paramsArr) !== false || $paramIndex = array_search($entryType.'__code', $paramsArr) !== false){
                    $code = $lineArr[$paramIndex];
                }else{
                    $code = md5($lineArr[$uniqueKey]);
                }
                */
                // если индексный параметр составной (section__id, element__id), то сохраняем в $uniqueVal только вторую часть - id - эта переменная является названием поля в БД
                
                $tmp = $paramsArr[$uniqueKey];
                
                $tmpArr = explode('__', $tmp);
                $uniqueVal = array_pop($tmpArr);
                unset($tmp);
               // print_r($lineArr);
                  
                
                //собираем массив полей с названиями, чтобы по нему потом строить структуру каталога
                //$currKeysSectionName = array();
                //foreach($arrKeysSectionName as $key=>$index){ //перебираем поля с названиями для тек. строки, удаляем пустые поля
                //    if(strlen(trim($lineArr[$index]))){
                //        $currKeysSectionName[] = $index;
                //    }
                //}
                //array_pop($currKeysSectionName); // удаляем последнее поле из массива названий - это название текущего товара/раздела
                /*
                $parentsNames = $currKeysSectionName;
                $parentsNames = array_reverse($parentsNames); //переворачиваем массив, чтобы начинать с наиболее близкого к тек.записи родителя
                
                foreach($parentsNames as $parentName){
                    //перебираем всех родителей
                    if(!empty($parentName)){ // если родитель не пустой, ищем в БД запись с указанным name родителя
                        //echo 'parentName in loop: '.$parentName."\n";
                        $parentEntry = $models['section']->model()->find('name=:parent_name', array(':parent_name'=>$lineArr[$parentName]));
                        if($parentEntry != null){ // если запись найдена, выставляем $parentId = id записи и $level = level записи + 1 - след. уровень вложенности                    
                            $parentId = $parentEntry->id;
                            $level = $parentEntry->level + 1;
                            break;
                        }
                        // если запись в БД не найдена, переходим к след. родителю и ищем его. В результате $parentId будет равен id первого найденного родителя, либо 0, если ни один родитель не найден
                    }
                }*/
                
                $newEntry = false; //флаг, показывающий создается новая запись или редактируется существующая.
                // ищем в БД запись с указанным уникальным ключем - индексом
                $model = Contragent::model()->find($uniqueVal.'=:searchParam', array(':searchParam'=>$lineArr[$uniqueKey]));
                if($model == null){//создаем новую запись
                    //echo 'Запись с '.$uniqueVal.' = '.$lineArr[$uniqueKey]." не найдена. будет создана новая запись\n";
                    $newEntry = true;
                    $model = new Contragent;
                }
                
                $newFields = array();
                $propertiesForEntry = array(); //в этот массив собираем свойства для данной записи/строки
                //print_r($model->attributes);
                //Перебираем все поля строки и присваиваем из значения атрибутам модели
                foreach($lineArr as $key => $value){ //TODO: вынести присваивание значений атрибутам после условия, т.к. это одинаково для новой записи и для изменения существующей
                    if(!strlen(trim($value))) continue; //пропускаем пустые поля
                    if($paramsArr[$key] == 'none') continue; //пропускаем поля, значение которых установлено "не использовать"
                    /*
                    if(preg_match('/^property__/', $paramsArr[$key])){ //Если поле в названии содержит указание на принадлежно к свойствам (property)
                        if(strlen(trim($value))){ //если это поле имеет значение
                            $propertiesForEntry[$paramsArr[$key]] = $value; //сохраняем это поле в массив свойств
                        }
                        continue; //пропускаем поля-свойства
                    }*/
                    $tmp = explode('__', $paramsArr[$key]);
                    $fieldName = array_pop($tmp);
                                                                        
                    if($fieldName == 'id') continue; //если текущее поле - id, пропускаем его, т.к. нельзя менять id у существующих записей.
                        
                    $newFields[$fieldName] = $value;
                }
                //$newFields[$parentIdField] = $parentId;
                //$newFields['code'] = $code;
                //if($entryType == 'section') $newFields['level'] = $level;
                //print_r($paramsArr);
                //print_r($newFields);
                $model->attributes = $newFields;
                //print_r($model->attributes);
                
                
                
                if($model->save()){
                    if($newEntry){
                        $response['create']['items'][] = $newFields['name'];
                        $response['create']['count'] += 1;
                    }else{
                        $response['update']['items'][] = $newFields['name'];
                        $response['update']['count'] += 1;
                    }
                    
                }else{
                    if($newEntry){
                        $response['errors']['items'][] = array(
                            'item' => $newFields['name'],
                            'message' => Yii::t('catalog', 'Ошибка при импортировании новой записи'),
                            'debug' => $model->errors,
                        );
                    }else{
                        $response['errors']['items'][] = array(
                            'item' => $newFields['name'],
                            'message' => Yii::t('catalog', 'Ошибка при импортировании существующей записи'),
                            'debug' => $model->errors,
                        );
                    }
                    $response['errors']['count'] += 1;
                } 
                
                // если запись - товар, то смотрим, есть ли свойства для него.
                /*
                if($entryType == 'element'){
                    if(!empty($propertiesForEntry) && $model != null){
                        // создаем модель для свойств, ключевой параметр - название свойства
                        
                        $PropertyModel = new CatalogProperty;
                        $PropValueModel = new CatalogTargetProperty;
                        
                        foreach($propertiesForEntry as $key=>$property){
                            $tmp = explode('__', $key);
                            $propertyField = array_pop($tmp);
                            $propertyValue = trim($property);
                            //echo $propertyField.' = '.$propertyValue."\n";
                            
                            $propModel = $PropertyModel->model()->find('code=:code', array(":code"=>$propertyField));
                            if($propModel == null){
                                // сохраняем свойство и значение в массив с ошибками, чтобы вывести потом пользователю и пропускаем это свойство 
                                $response['errors']['items'][] = array(
                                    'item' => $propertyField,
                                    'message' => Yii::t('catalog', 'Ошибка! Свойство '.$propertyField.' не найдено в БД.')
                                );
                                continue;
                            }
                            $valModel = $PropValueModel->model()->find('property_id=:property_id AND target_id=:target_id', array(':property_id'=>$propModel->id, ':target_id'=>$model->id));
                            if($valModel == null){
                                $valModel = new CatalogTargetProperty;
                            }
                            $valModel->property_id = $propModel->id;
                            $valModel->target_id = $model->id;
                            $valModel->value = $propertyValue;
                            if(!$valModel->save()){
                                $response['errors']['items'][] = array(
                                    'item' => $propModel->name,
                                    'message' => Yii::t('catalog', 'Ошибка при сохранении свойства товара')
                                );
                            }
                        }
                    }
                } */
            }
            //print_r($response);
            echo json_encode($response);
            //print_r($models);

            if(!feof($fp)){
                throw new CHttpException(404, Yii::t('catalog', 'Ошибка! Не достигнут конец файла!'));
            }
            fclose($fp);
            return '$parseContent';
        }
}
?>
