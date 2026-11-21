<?php

class UpdateAction extends CAction{

    public function run($id, $type){
        switch ($type){
            case 'iblock':
                $this->updateIblock($id);
                break;
            case 'section':
                $this->updateSection($id);
                break;
            case 'element':
                $this->updateElement($id);
                break;
            default:
                echo 'ошибка! неверный параметр';
                break;
        }

        // Uncomment the following line if AJAX validation is needed
	//$this->controller->performAjaxValidation($model);

    }


    /** Update Infoblock */
    protected function updateIblock($id){
        $model = IBlock::model()->findByPk($id);

        if(isset($_POST['IBlock'])){

            //$model->attributes = $_POST['IBlock'];
            foreach($_POST['IBlock'] as $key => $newVal){
                if($key == 'properties' || $key == 'new_properties') continue;
                $model->$key = $newVal;
            }

            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('iblock', 'Все изменения сохранены.'));
                    if(!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(array('/iblock/admin/index/'));
                }

            }
        }

        $properties = array();
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and section_id=0';
        $criteria->params = array(
            ':iblock_id'=> $model->id,
        );
        $criteria->order = 'sort ASC';
        $properties = IBlockProperty::model()->findAll($criteria);

        $this->controller->render('updateIblock', array('model'=>$model, 'properties'=>$properties));
    }

    /* Update section of infoblock */
    protected  function updateSection($id){
        $model = IBlockSection::model()->findByPk($id);
        $iblock = IBlock::model()->findByPk($model->iblock_id);

        if(isset($_POST['IBlockSection'])){
            $model->attributes = $_POST['IBlockSection'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('iblock', 'Все имзенения сохранены.'));
                    if(!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
        }

        $properties = array();
        $criteria = new CDbCriteria();
        $criteria->condition = 'iblock_id=:iblock_id and (section_id=:section_id or section_id=0)';
        $criteria->params = array(
            ':iblock_id'=> $iblock->id,
            ':section_id'=>$id,
        );
        $criteria->order = 'sort ASC';
        $properties = IBlockProperty::model()->findAll($criteria);

        $this->controller->render('updateSection', array('model'=>$model, 'iblock'=>$iblock, 'properties'=>$properties));
    }

    /* Update element of infoblock */
    protected function updateElement($id)
    {
        $model = IBlockElement::model()->findByPk($id);
        $iblock = IBlock::model()->findByPk($model->iblock_id);
        $section = IBlockSection::model()->findByPk($model->iblock_section_id);

        if(isset($_POST['IBlockElement'])){
            $active_from = date('Y-m-d H:i:s', strtotime($_POST['IBlockElement']['active_from']['date'].' '.$_POST['IBlockElement']['active_from']['time']));
            $_POST['IBlockElement']['active_from'] = $active_from;
            $_POST['IBlockElement']['preview_picture'] = Misc::normalizeFilePath($_POST['IBlockElement']['preview_picture']);
            $_POST['IBlockElement']['detail_picture'] = Misc::normalizeFilePath($_POST['IBlockElement']['detail_picture']);

            $model->attributes = $_POST['IBlockElement'];
            if($model->validate()){
                if($model->save(false)){
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('iblock', 'Все изменения сохранены.'));
                    if(!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(Yii::app()->user->returnUrl);
                }
            }
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

        $this->controller->render('updateElement', array('model'=>$model, 'iblock'=>$iblock, 'section'=>isset($section)?$section:null, 'properties'=>$properties));
    }

}
