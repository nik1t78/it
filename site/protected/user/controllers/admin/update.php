<?php $this->pageTitle = Yii::t('user', 'Управление пользователями'); ?>
<?php $this->bodyBlockTitle = Yii::t('user', 'Редактирование пользователя'); ?>

<?php 
$this->breadcrumbs=array(
	$this->module->getName()=>array('list'),
	(!empty($model->full_name)?$model->full_name:$model->name)=>array('view','id'=>$model->id),
	'Редактирование',
);
?>



<div class="widget">
	<div class="title">
            <img class="titleIcon" alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/dark/pencil.png" />
            <h6><?php echo $this->bodyBlockTitle; ?></h6>
        </div>

        <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    
</div>