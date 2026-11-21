<?php
class CallbackAction extends CAction
{
    public function run()
    {
        /**
	 * Displays the contact page
	 */
        $model=new CallbackForm;
        if (isset($_POST['CallbackForm'])) {
            $model->attributes=$_POST['CallbackForm'];
            if ($model->validate()) {
                $adminEmail = Yii::app()->params->adminEmail; //Email менеджера, берется из настроек сайта
                $emailer = new EmailMessenger;
                $emailer->send($adminEmail, "Заказ обратного звонка", "С сайта поступила просьба перезвонить по тел. $model->phone Контактное лицо: $model->name");
                Yii::app()->user->setFlash('callback','Спасибо за Ваше обращение. Мы перезвоним Вам в самое ближайшее время.');
                $this->controller->refresh();
            }
        }
        $this->controller->render('callback',array('model'=>$model));
    }
}
?>
