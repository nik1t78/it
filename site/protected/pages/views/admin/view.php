<?php $this->pageTitle = Yii::t('pages', 'Управление структурой'); ?>
<h1>Управление структурой</h1>
<?php 
$this->breadcrumbs=array(
	$this->module->getName()=>array('list'),
//	$model->name
);
?>
<?php $this->widget('AFlashMessages'); ?>
<div id="userDetailView">
    <h3><?php //echo $model->name; ?> <span class="userid">id: <?php //echo $model->id; ?></span></h3>
    <div class="row">
        <span class="fieldName">Описание: </span> <span class="fieldValue"><?php //echo $model->description; ?></span>
    </div>
    <div class="row">
        <span class="fieldName">Статичные пункты: </span> <span class="fieldValue"> <code><?php //echo htmlspecialchars($model->manual); ?></code></span>
    </div>
    <div class="row buttons">
        <div class="button-type2"><a href="<?php //echo Yii::app()->createUrl('/menu/admin/update/', array('id' => $model->id)); ?>">Редактировать</a></div>
        <div class="button-type3"><a href="<?php //echo Yii::app()->createUrl('/menu/admin/list/'); ?>">Назад</a></div>        
    </div>
</div>