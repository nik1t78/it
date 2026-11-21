<?php
class ContactAction extends CAction{
    
    public function run(){
        /**
	 * Displays the contact page
	 */
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$headers="From: {$model->email}\r\nReply-To: {$model->email}";
                                $emailer = new EmailMessenger;
                                $emailer->from = Yii::app()->params['adminEmail'];
                                $emailer->send(Yii::app()->params['adminEmail'], $model->subject, $this->controller->renderPartial('emailFeedback', array('model'=>$model), true)); //письмо менеджеру
                                
				//mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Спасибо за Ваше сообщение. Мы ответим Вам в самое ближайшее время.');
				$this->controller->refresh();
			}
		}
		$this->controller->render('contact',array('model'=>$model));
    }
}
?>
