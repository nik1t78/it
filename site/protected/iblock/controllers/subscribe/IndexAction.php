<?php

class IndexAction extends CAction
{
    public $iblock_id = 11;
    
    public function run()
    {
        $model = new SubscribeForm;
        
        if (isset($_POST['SubscribeForm'])) {
            $model->attributes = $_POST['SubscribeForm'];
            
            if ($model->validate()) {
                if (array_key_exists('subscribeBtn', $_POST)) {
                    //Пользователь подписывается
                    $subscribe = new IBlockSubscribe;
                    $subscribe->iblock_id = $this->iblock_id;
                    $subscribe->email = $model->email;
                    if ($subscribe->save()) {
                        $result = Yii::t('iblock', 'Вы успешно подписались на рассылку.'); 
                    } else {
                        $result = Yii::t('iblock', 'Возникла ошибка. Попробуйте повторить попытку позже.');
                    }
                } elseif (array_key_exists('unsubscribeBtn', $_POST)) {
                    //Пользователь отписывается
                    $subscribe = IBlockSubscribe::model()->findByAttributes(array('email'=>$model->email));
                    if ($subscribe == null) {
                        $result = Yii::t('iblock', 'Указанный Email не найден в базе данных.');
                    } else {
                        if ($subscribe->delete()) {
                            $result = Yii::t('iblock', 'Вы успешно отписались от рассылки.');
                        } else {
                            $result = Yii::t('iblock', 'Возникла ошибка. Попробуйте повторить попытку позже.');
                        }
                    }
                }
                
                $this->controller->render('result', array('result'=>$result));
                return true;
            }
        }
        
        $this->controller->render('index', array('model' => $model));
    }
}
