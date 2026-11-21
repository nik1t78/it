<?php

class UpdateAction extends CAction
{
    public function run($id)
    {
        $model = Polls::model()->findByPk($id);
        $answers = PollsAnswers::model()->findAllByAttributes(array('poll_id'=>$id));
        
        if (isset($_POST['Polls'])) {                        
            $active_from = date('Y-m-d H:i:s', strtotime($_POST['Polls']['active_from']['date'].' '.$_POST['Polls']['active_from']['time']));               
            $active_to = date('Y-m-d H:i:s', strtotime($_POST['Polls']['active_to']['date'].' '.$_POST['Polls']['active_to']['time']));
            $_POST['Polls']['active_from'] = $active_from;
            $_POST['Polls']['active_to'] = $active_to;
            
            $model->attributes = $_POST['Polls'];
            
            if ($model->validate()) {
                if ($model->save(false)) {                    
                    Yii::app()->user->setFlash(AFlashMessages::SUCCESS_MESSAGE, Yii::t('polls', 'Опрос успешно сохранен.'));
                    $this->controller->redirect(array('/polls/admin/index'));
                }
            }
        }
        
        $this->controller->render('update', array('model'=>$model, 'answers' => $answers));
    }
}
