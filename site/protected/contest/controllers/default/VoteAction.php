<?php

class VoteAction extends CAction
{
    private $_ipAddress;

    public function getIp()
    {
        if (isset($_SERVER['REMOTE_ADDR']) AND isset($_SERVER['HTTP_CLIENT_IP']))
            $this->_ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        elseif (isset($_SERVER['REMOTE_ADDR']))
            $this->_ipAddress = $_SERVER['REMOTE_ADDR'];
        elseif(isset($_SERVER['HTTP_CLIENT_IP']))
            $this->_ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $this->_ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }

    protected function vote($id)
    {
        $member = ContestMember::model()->findByPk($id);
        if ($member === null) {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Участник не найден'
            ));
        } else {
            //проверка cookie
            if (Yii::app()->session->get(md5('contestVote'))) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Вы уже голосовали'
                ));
                Yii::app()->end();
            }

            //Проверка IP
            $criteriaVoter = new CDbCriteria();
            $criteriaVoter->condition = 'ip=:ip';
            //$criteriaVoter->addCondition('session_key=:session');
            $criteriaVoter->params = array(
                ':ip' => $this->_ipAddress
            );
            $voter = ContestVoter::model()->find($criteriaVoter);
            if ($voter !== null) {
                echo json_encode(array(
                    'status' => 'error',
                    'message' => 'Вы уже голосовали'
                ));
                Yii::app()->end();
            }


            $member->vote += 1;
            $member->save(false);

            Yii::app()->session->add(md5('contestVote'), true);
            $voter = new ContestVoter();
            $voter->ip = $this->_ipAddress;
            $voter->member_id = $member->id;
            //$voter->session_key = '';
            $voter->save(false);

            echo json_encode(array(
                'status' => 'success',
                'result' => $member->vote
            ));
        }
    }

    public function run($id)
    {
        if (Yii::app()->request->isAjaxRequest) {

            //brainfuck
            /*$this->brainfuck(439);
            $this->brainfuck(454);
            $this->brainfuck(414);
            $this->brainfuck(444);
            $this->brainfuck(376);
            $this->brainfuck(379);
            $this->brainfuck(406);
            $this->brainfuck(412);*/

            $this->getIp();
            $this->vote($id);

            Yii::app()->end();
        } else {
            throw new CHttpException(500, Yii::t('system', 'Неверный запрос.'));
        }
    }

    protected function brainfuck($id)
    {
        $this->_ipAddress = rand(0, 255).'.'.rand(0, 255).'.'.rand(0, 255).'.'.rand(0, 255);

        $member = ContestMember::model()->findByPk($id);
        $member->vote += 1;
        $member->save(false);


        $voter = new ContestVoter();
        $voter->ip = $this->_ipAddress;
        $voter->member_id = $member->id;
        //$voter->session_key = '';
        $voter->save(false);
    }
}