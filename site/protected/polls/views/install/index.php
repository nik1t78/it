<?php $this->pageTitle = Yii::t('user', 'Управление пользователями'); ?>
<?php $this->bodyBlockTitle = Yii::t('user', 'Установка модуля'); ?>

<?php 
$this->breadcrumbs=array(
	$this->module->getName(),
);
?>



<div class="widget">
	<div class="title">
            <img class="titleIcon" alt="" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/icons/dark/pencil.png" />
            <h6><?php echo $this->bodyBlockTitle; ?></h6>
        </div>
        Установка модуля        
</div>