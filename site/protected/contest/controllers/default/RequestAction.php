<?php

class RequestAction extends CAction
{
    public function run($contest_id)
    {
        $model = new ContestRequest();
        $model->contest = $contest_id;

        if (isset($_POST['ContestRequest'])) {
            $model->attributes = $_POST['ContestRequest'];

            $dbModel = new ContestMember();
            $dbModel->attributes = $_POST['ContestRequest'];
            $dbModel->contest_id = $contest_id;


            if ($model->validate() && $dbModel->validate()) {
                $dbModel->save(false);
                //$attach = file_exists(Yii::getPathOfAlias('webroot').$model->file) ? Yii::getPathOfAlias('webroot').$model->file : false;
                $attach = array();
//                if (file_exists(Yii::getPathOfAlias('webroot').$model->photo1) && !is_dir(Yii::getPathOfAlias('webroot').$model->photo1))
//                   $attach[] = Yii::getPathOfAlias('webroot').$model->photo1;
//                if (file_exists(Yii::getPathOfAlias('webroot').$model->photo2) && !is_dir(Yii::getPathOfAlias('webroot').$model->photo2))
//                   $attach[] = Yii::getPathOfAlias('webroot').$model->photo2;
//                if (file_exists(Yii::getPathOfAlias('webroot').$model->photo3) && !is_dir(Yii::getPathOfAlias('webroot').$model->photo3))
//                   $attach[] = Yii::getPathOfAlias('webroot').$model->photo3;
//                if (file_exists(Yii::getPathOfAlias('webroot').$model->photo4) && !is_dir(Yii::getPathOfAlias('webroot').$model->photo4))
//                   $attach[] = Yii::getPathOfAlias('webroot').$model->photo4;
                $contest = Contest::model()->findByPk($model->contest);
                $emailer = new EmailMessenger;
                $emailer->from = Yii::app()->params['adminEmail'];
                /*
                $result = $emailer->send(
                        Yii::app()->params['konkursEmail'],
                        'Сайт Управления социальной защиты населения Липецкой области',
                        $this->controller->renderPartial('emailRequest', array('model'=>$model, 'contest'=>$contest), true),
                        false,
                        $attach
                );*/

//                foreach ($attach as $file) {
//                    unlink($file);
//                }


                $this->controller->render('result', array(
                    'message'=>'Ваша заявка ушла в обработку и после модерации будет добавлена в конкурс.',
                    //'log' => $result
                ));
                return true;
            }
        }

//        $criteria = new CDbCriteria();
//        $criteria->condition = "contest_id=:contest_id";
//        $criteria->params = array(':contest_id' => $contest_id);
//        $params = ContestParam::model()->findAll($criteria);
        $criteria = new CDbCriteria();
        $criteria->condition = "contest_id=:contest_id";
        $criteria->params = array(':contest_id' => $contest_id);
        $groups = ContestParamGroup::model()->findAll($criteria);

        $this->controller->render('request', array(
            'model'=>$model,
            'groups' => $groups,
            //'params' => $params
        ));
    }
}