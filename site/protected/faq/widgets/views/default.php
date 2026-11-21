<h2>Задать вопрос</h2>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'faq-form',
        //'action'=>Yii::app()->createUrl('faq/default/sendQuestion'),
	'enableAjaxValidation'=>false,
        'htmlOptions'=>array(                    
            'class' => 'form formFaq',
        ),
)); 
?>
    <div class="row">
       <?php echo $form->errorSummary($model); ?> 
       <?php echo $form->hiddenField($model, 'q_date', array('value'=>date('d.m.Y'))); ?>
    </div>    
    <div class="row">
        <?php echo $form->labelEx($model, 'q_author'); ?>
        <div class="textFieldBg"><?php echo $form->textField($model, 'q_author', array('class'=>'textField')); ?></div>
        <div class="clear"></div>
        <?php echo $form->error($model, 'q_author'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'q_email'); ?>
        <div class="textFieldBg"><?php echo $form->textField($model, 'q_email', array('class'=>'textField')); ?></div>
        <div class="clear"></div>
        <?php echo $form->error($model, 'q_email'); ?>
    </div>
    <div class="row">
        <?php echo $form->labelEx($model, 'question'); ?>
        <div class="textAreaBg"><?php echo $form->textArea($model, 'question', array('class'=>'textArea')); ?></div>
        <div class="clear"></div>
        <?php echo $form->error($model, 'question'); ?>
    </div>

    <div class="row submit">
        <div class="formButton1"><?php echo CHtml::submitButton('Отправить'); ?></div>
        <div class="formButton1"><?php echo CHtml::resetButton('Отменить', array('class'=>'button redB')); ?></div>
    </div>	

<?php $this->endWidget(); ?>