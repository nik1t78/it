<?php

class Security 
{
    static public function checkPermission($role)
    {
        $result = false;
        if (is_array($role)) {
            //Если передан массив с ролями
            foreach ($role as $item) {
                if (Yii::app()->user->checkAccess($item)) 
                    $result = true;
            }
        } else {
            //Если не массив - передана одна роль
        }
        
        
        if(!Yii::app()->user->checkAccess('pagesManagement')){            
            Yii::app()->user->setFlash(AFlashMessages::ERROR_MESSAGE, Yii::t('system', 'Ваших прав недостаточно для доступа к этой странице.'));
            $this->redirect(array('/backend/'));
        }
    }
}
