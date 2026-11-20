<?php

class CreateAction extends CAction{
    
    public function run($type, $iblock=0, $section=0){
        //$type_name = ucfirst(strtolower($type));
        switch ($type){
            case 'iblock':
                $this->createIblock();
                break;
            case 'section':
                $this->createSection($iblock, $section);
                break;
            case 'element':
                $this->createElement($iblock, $section);
                break;
            default:
                echo 'ошибка! неверный параметр';
                break;
        }  
    }
    
    /** Create new Infoblock */
    protected function createIblock(){
        $model = new IBlock;
        
        if(isset($_POST['IBlock'])){
            $model->attributes = $_POST['IBlock'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('iblock', 'Инфоблок "'.$model->name.'" успешно создан.'));                    
                    //if(!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(array('/iblock/admin/index'));
                }
                
            }
        }
        
        $this->controller->render('createIblock', array('model'=>$model));
    }
    
    /* Create new section of infoblock */
    protected  function createSection($iblock_id, $section_id){
        $model = new IBlockSection;
        
        if(isset($_POST['IBlockSection'])){
            $model->attributes = $_POST['IBlockSection'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('iblock', 'Раздел "'.$model->name.'" успешно добавлен.'));
                    
                    //if(!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        
        
        if($iblock_id != 0){        
            $iblock = IBlock::model()->findByPk($iblock_id);
            if($iblock == null) 
                throw new CHttpException(404, Yii::t('iblock', 'Инфоблок с id: '.$iblock_id.' не найден.'));
            $model->iblock_id = $iblock_id;
        }
        
        if($section_id != 0){
            $section = IBlockSection::model()->findByPk($section_id, 'iblock_id=:iblock_id', array(':iblock_id'=>$iblock_id));
            if($section == null)
                throw new CHttpException(404, Yii::t('iblock', 'Раздел с id: '.$section_id.' в инфоблоке "'.$iblock->name.'" не найден.'));
            $model->iblock_section_id = $section_id;
        }
        
        
        $properties = array();
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and (section_id=0)';
        $criteria->params = array(
            ':iblock_id'=> $iblock->id,
            //':section_id'=>$id, //Если нужно наследовать свойства не только от инфоблока, но и от родительского раздела - добавить условие
        );
        $criteria->order = 'sort ASC';
        $properties = IBlockProperty::model()->findAll($criteria);
        
        $this->controller->render('createSection', array('model'=>$model, 'iblock'=>$iblock, 'properties'=>$properties));
    }
    
    /* Create new element of infoblock */
    protected function createElement($iblock_id, $section_id){
        $model = new IBlockElement;
        
        if(isset($_POST['IBlockElement'])){
            $active_from = date('Y-m-d H:i:s', strtotime($_POST['IBlockElement']['active_from']['date'].' '.$_POST['IBlockElement']['active_from']['time']));
            $_POST['IBlockElement']['active_from'] = $active_from;
            $_POST['IBlockElement']['preview_picture'] = Misc::normalizeFilePath($_POST['IBlockElement']['preview_picture']);
            $_POST['IBlockElement']['detail_picture'] = Misc::normalizeFilePath($_POST['IBlockElement']['detail_picture']);
            
            $model->attributes = $_POST['IBlockElement'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('iblock', 'Элемент "'.$model->name.'" успешно добавлен.'));
                    $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }
        if($iblock_id != 0){        
            $iblock = IBlock::model()->findByPk($iblock_id);
            if($iblock == null) 
                throw new CHttpException(404, Yii::t('iblock', 'Инфоблок с id: '.$iblock_id.' не найден.'));
            $model->iblock_id = $iblock_id;
        }
        
        if($section_id != 0){
            $section = IBlockSection::model()->findByPk($section_id, 'iblock_id=:iblock_id', array(':iblock_id'=>$iblock_id));
            if($section == null)
                throw new CHttpException(404, Yii::t('iblock', 'Раздел с id: '.$section_id.' в инфоблоке "'.$iblock->name.'" не найден.'));
            $model->iblock_section_id = $section_id;
        }
        
        $properties = array();
        // Получаем свойства инфоблока
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and (section_id=0 or section_id=:section_id ) and active=:active';
        $criteria->params = array(
            ':iblock_id' => $iblock->id,
            ':section_id' => isset($section) ? $section->id : 0,
            ':active' => IBlock::ACTIVE,
        );
        $criteria->order = 'sort ASC';
        $iblock_props = IBlockProperty::model()->findAll($criteria);
        // Записываем свойства инфоблока в массив с ключем Код
        foreach($iblock_props as $prop){
            $properties[$prop->code] = $prop;
        } 
        /* Из-за этого при создании новости в свойствах висели все свойства из всех инфоблоков, да еще и второе обращение к БД
        // Получаем свойства раздела
        $criteria = new CDbCriteria();
        $criteria->condition = 'section_id=:section_id and iblock_id=:iblock_id and active=:active';
        $criteria->params = array(
            ':section_id' => isset($section)?$section->id:0,
            ':active' => IBlock::ACTIVE,
        );
        $criteria->order = 'sort ASC';
        $section_props = IBlockProperty::model()->findAll($criteria); 
        // Записываем свойства раздела в массив с ключем Код. При совпадении ключа со свойством, унаследованным от инфоблока, свойство раздела имеет приоритет.
        foreach($section_props as $prop){
            $properties[$prop->code] = $prop;
        }
        */
        $this->controller->render('createElement', array('model'=>$model, 'iblock'=>isset($iblock)?$iblock:0, 'section'=>isset($section)?$section:0, 'properties'=>$properties));
    }
}
