<?php
Yii::import('menu.models.*');

class DeleteAction extends CAction
{
    public function run($id, $type='menu'){
        if(Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            // $this->controller->loadModel($id)->delete();
            if ($type === 'menu') {
                $user = Menu::model()->findByPk($id);
                if(!$user->delete()){
                    echo Yii::t('artcms', 'Ошибка при удалении записи');//Вывод сообщения на экран. сейчас в POST пишет
                    Yii::app()->end();
                }
            } else if ($type === 'item') {
                $model = MenuItems::model()->findByPk($id);
                if (!$model->delete()) {
                    echo json_encode(array(
                        'status' => 'error',
                        'message' => 'При удалении записи возникла ошибка.'
                    ));
                    Yii::app()->end();
                } else {
                    echo json_encode(array(
                        'status' => 'success'
                    ));
                    Yii::app()->end();
                }
            }
            


            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
	    if(!isset($_GET['ajax']))
                $this->controller->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('/backend/menu/'));
        } else {
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. Пожалуйста, повторите попытку позже.'));
        }
    }
}