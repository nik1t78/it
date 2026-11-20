<?php $this->pageTitle = Yii::t('menu', 'Создание меню'); ?>
<h1>Создание меню</h1>
<?php 
$this->breadcrumbs=array(
	$this->module->getName()=>array('list'),
	'Создание меню',
);
?>
<?php $this->widget('AFlashMessages'); ?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>