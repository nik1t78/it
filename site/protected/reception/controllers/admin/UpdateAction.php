<?php

class UpdateAction extends CAction
{
    public function run($id)
    {
        $model = Reception::model()->findByPk($id);
        
        if ($model === null)
            throw new CDbException(Yii::t('reception','Запрошенное обращение не найдено'));
        
        if (isset($_POST['Reception'])) {
            if (isset($_POST['Reception']['date'])) {
                $date = date('Y-m-d', strtotime($_POST['Reception']['date']));
                $_POST['Reception']['date'] = $date;
            }
            
            $model->attributes = $_POST['Reception'];
            
            if ($model->save()) {
            //VarDumper::dump($model->attributes);
                Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('reception', 'Обращение успешно сохранено.'));
                if (!array_key_exists('applyChanges', $_POST)) {
                    $this->controller->redirect(array('/reception/admin/index'));
                }
            }
        }
        
        $this->controller->render('update', array('model'=>$model));
    }
}
