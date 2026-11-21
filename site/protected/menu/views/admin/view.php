<?php $this->pageTitle = Yii::t('menu', 'Управление меню'); ?>
<h1>Управление меню</h1>
<?php 
$this->breadcrumbs=array(
	$this->module->getName()=>array('list'),
	$model->name
);
?>
<?php $this->widget('AFlashMessages'); ?>
<div id="userDetailView">
    <h3><?php echo $model->name; ?> <span class="userid">id: <?php echo $model->id; ?></span></h3>
    <div class="row">
        <span class="fieldName">Описание: </span> <span class="fieldValue"><?php echo $model->description; ?></span>
    </div>
    <div class="row">
        <span class="fieldName">Элементы меню: </span> <span class="fieldValue"><div class="menuItemsList">
        	<?php foreach($model->menuItems as $menuItem): ?>
        		<a href="#"><?php echo $menuItem->title; ?></a>
        	<?php endforeach; ?>
        </div></span>
    </div>
    <div class="row">
        <span class="fieldName">Статичные элементы: </span> <span class="fieldValue"><div class="menuItemsList"><?php echo $model->manual; ?></div></span>
    </div>
    <div class="row buttons">
        <div class="button-type2"><a href="<?php echo Yii::app()->createUrl('/menu/admin/update/', array('id' => $model->id)); ?>">Редактировать</a></div>
        <div class="button-type3"><a href="<?php echo Yii::app()->createUrl('/menu/admin/list/'); ?>">Назад</a></div>        
    </div>
</div>