<?php

class UpdateAction extends CAction
{
    public function run($id)
    {                 
        $model=$this->controller->loadModel($id);

        $permission_type = 'deny';
        if (!empty($model->permissions)) {
            $tmp = explode(':', $model->permissions); 
            $permission_type = $tmp[0];
            $model->permissions = explode(';', $tmp[1]);
        }
        if (Yii::app()->user->role != 'admin') {
            if (!$this->checkPermission($model)) {  //Проверка прав
                Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа к этой странице'));
                $this->controller->redirect(array('/pages/admin'));
            }
        }
        
        // Uncomment the following line if AJAX validation is needed
        $this->controller->performAjaxValidation($model);
                
        if (isset($_POST['Pages'])) {
            if ($id == 1) { //Редактируем корневой раздел
                $_POST['Pages']['parent_id'] = 0;
            }
            $changeParentFlag = $model->attributes['parent_id'] == $_POST['Pages']['parent_id'] ? false : true;
            
            $model->attributes=$_POST['Pages'];
            //VarDumper::dump($_POST);
            if (!empty($_POST['Pages']['permissions'])) {            
                $permission = $_POST['Permission']['type'] . ':';
                $sep = '';
                
                foreach ($_POST['Pages']['permissions'] as $per) {
                    $permission .= $sep.$per;
                    $sep = ';';
                }
                $model->permissions = $permission;
            }
            
            
            
            if ($model->validate()) {
                //if($model->save())                
               
                //$x100=Category::model()->findByPk(10);
                $parent=Pages::model()->findByPk($model->parent_id);
                
                if ($changeParentFlag) {
                    if (!$model->moveAsLast($parent))
                        throw new CHttpException(400, Yii::t('artcms', 'Не удалось изменить расположение раздела в структуре сайта. Попытайтесь еще раз. '));
                }
                
                if ($model->saveNode()) {
                    if(!array_key_exists('applyChanges', $_POST))
                        $this->controller->redirect(array('index'));
                }
            }			
        }

        $this->controller->render('update',array(
            'model'=>$model,
            'permission_type' => $permission_type
        ));       
    }

    protected function checkPermission($model)
    {
        $permission_type = 'deny';
        if (!empty($model->permissions)) {
            if (!is_array($model->permissions)) {
                $tmp = explode(':', $model->permissions);
                $permission_type = $tmp[0];
                $model->permissions = explode(';', $tmp[1]);
            }

            if ($permission_type == 'allow') {
                if (array_search(Yii::app()->user->role, $model->permissions) === false) {
                    return false;
                }
            } else {
                if (array_search(Yii::app()->user->role, $model->permissions) !== false) {
                    return false;
                }
            }

        } else {
            //VarDumper::dump($model);
            if ($model->parent_id != 0) {
                return $this->checkPermission($model->parent);
            }
        }
        
        return true;
    }
}