<?php $this->pageTitle = Yii::t('menu', 'Редактирование меню'); ?>
<h1>Редактирование меню</h1>
<div class="bodyBlock">
	<h3 class="bodyBlockHead"></h3>
	<div class="bodyBlockContent">
<?php 
$this->breadcrumbs=array(
	$this->module->getName()=>array('list'),
	$model->name=>array('view','id'=>$model->id),
	'Редактирование',
);
?>
<?php $this->widget('AFlashMessages'); ?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>	
	</div>
</div>

