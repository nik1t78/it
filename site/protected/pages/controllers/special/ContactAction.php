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
				mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Спасибо за Ваше сообщение. Мы ответим Вам в самое ближайшее время.');
				$this->controller->refresh();
			}
		}
		$this->controller->render('contact',array('model'=>$model));
    }
}
?>
