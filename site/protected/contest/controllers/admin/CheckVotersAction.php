<?php

class CheckVotersAction extends CAction
{
    public function run($id)
    {
        $model = Contest::model()->findByPk($id);
        $result = array();
        foreach ($model->members as $member) {
            $criteria = new CDbCriteria;
            $criteria->condition = 'member_id=:mid';
            $criteria->params = array(
                ':mid' => $member->id,
            );
            $result[$member->id] = array(
                'model' => $member,
				'votes'=> array()
            );
            $votes = ContestVoter::model()->findAll($criteria);
            foreach ($votes as $vote) {
                $country = $this->checkVote($vote->ip);
                if ($country !== 'RU') {
                    $result[$member->id]['votes']['fail'][$vote->id] = array(
                        'ip' => $vote->ip,
                        'country' => $country
                    );
                } else {
                    $result[$member->id]['votes']['success'][$vote->id] = array(
                        'ip' => $vote->ip,
                        'country' => $country
                    );
                }
            }
        }
        $this->controller->render('check', array('model' => $model, 'result' => $result));
    }

    private function checkVote($ip)
    {
        include('tabgeo_country_v4/tabgeo_country_v4.php');
        $country_code = tabgeo_country_v4($ip);
        return $country_code;
    }
}
