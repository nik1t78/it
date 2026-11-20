<?php

class DefaultController extends FrontendController
{
    public $template = 'defalut';
    public $bodyPageTitle = '';
    
    public function actionIndex()
    {
        $this->render('index', array());
    }
    
    public function actionArchive()
    {
        $criteria = new CDbCriteria;
        $criteria->condition = 't.active_from < :curr_date AND t.active_to < :curr_date';
        $criteria->params = array(
            ':curr_date' => date('Y-m-d H:i:s')
        );
        $criteria->order = 't.active_to DESC';
        
        $count = Polls::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        
        $polls = Polls::model()->findAll($criteria);
        $this->render('archive', array('polls' => $polls, 'pages' => $pages));
    }
    
    public function actionAjax()
    {
        if (Yii::app()->request->isAjaxRequest) {
            if (empty($_POST['answer'])) {
                echo json_encode(array('message' => 'Ошибка! Возможно не выбран вариант ответа.'));
                Yii::app()->end();
            }
            $answer = explode('=', $_POST['answer']); //poll_answer=19
            
            
            if (isset($_POST['poll_id']) && isset($answer[1])) {
                $answer_id = $answer[1];
                $poll = Polls::model()->findByPk($_POST['poll_id']);
                if ($poll == null){
                    echo json_encode(array('message' => 'Ошибка! Опрос не найден.'));
                    Yii::app()->end();
                }
                
                $clientIp = $_SERVER["REMOTE_ADDR"];
                
                //Проверка по IP
                
                $clientVote = PollsCount::model()->findByAttributes(array('poll_id'=>$poll->id, 'ip'=>$clientIp));
                if ($clientVote !== null) {
                    $result = array(
                        'message' => 'Вы уже голосовали.',
                        'results' => $this->getResult($poll)
                    );
                    echo json_encode($result);
                    Yii::app()->end();
                }
                
                $count = new PollsCount();
                $count->ip = $clientIp;
                $count->answer_id = $answer_id;
                $count->poll_id = $poll->id;
                if ($count->save(false)){
                    $result = array(
                        'message' => 'Спасибо! Ваш голос учтён.',
                        'results' => $this->getResult($poll)
                    );
                    echo json_encode($result);
                    
                    Yii::app()->end();
                } else{
                    echo json_encode(array('message' => 'Возникла ошибка. Попробуйте повторить попытку позже'));
                    Yii::app()->end();
                }
                
            } else {
                echo json_encode(array('message' => 'Ошибка! Попробуйте повторить попытку позже!'));
                Yii::app()->end();
            }
            
        } else {
            throw new CHttpException(400, Yii::t('artcms', 'Не верный запрос. '));
        }
    }
    
    public function getResult($poll)
    {
        $allVotes = PollsCount::model()->findAllByAttributes(array('poll_id'=>$poll->id));
        $totalCount = count($allVotes);
        $result = array();
        $html = '';
        
        foreach ($allVotes as $vote) {
            if (!isset($result[$vote->answer_id])) $result[$vote->answer_id] = 1;
            else $result[$vote->answer_id]++;
        }
        
        $answers = PollsAnswers::model()->findAllByAttributes(array('poll_id'=>$poll->id));
        
        foreach ($answers as $answer) {
            $count = isset($result[$answer->id]) ? $result[$answer->id] : 0;
            if ($count > 0)
                $percent = number_format($count * 100 / $totalCount, 0);
            else 
                $percent = 0;
            $html .= '<div class="answer_result"><span style="width: '.$percent.'%;"></span><i>'.$answer->title.' ('.$percent.'%)</i></div>';
        }
        
        return $html;
    }
    
    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    public function performAjaxValidation($model)
    {
	if(isset($_POST['ajax']) && $_POST['ajax']==='dou-form')
	{
            echo CActiveForm::validate($model);
            Yii::app()->end();
	}
    }
}