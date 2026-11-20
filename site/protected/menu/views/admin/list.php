<?php $this->pageTitle = Yii::t('menu', 'Управление меню'); ?>
<h1>Управление меню</h1>

<?php 
$this->breadcrumbs=array(
	$this->module->getName(),
);
?>
<?php $this->widget('AFlashMessages'); ?>

<?php  
    $this->widget('zii.widgets.grid.CGridView', array(
        'dataProvider'=>$dataProvider,
        'columns' => array(
            array(
                'name' => 'id', 
                'type' => 'raw',
                'value' => 'CHtml::link(CHtml::encode($data->id), Yii::app()->createUrl(\'/menu/admin/view/\', array(\'id\'=>$data->id)))'
            ),
            array(
                'name' => 'name', 
                'type' => 'raw',
                'value' => '$data->name'
            ),
            array(
                'name' => 'description',  
                'type' => 'raw',
                'value' => '$data->description'
            ),
            array('class' => 'CButtonColumn',
                //'deleteButtonOptions' => array('rel'=>'test'),
                //'deleteButtonUrl' => 'Yii::app()->createUrl(\'/users/admin/delete/\', array(\'id\'=>$data->id))'
            )
        ),
        'cssFile' => Yii::app()->theme->baseUrl.'/css/ziiGrid.css',
    ));
?>
<div class="button-type2"><a href="<?php echo Yii::app()->createUrl('/menu/admin/create/'); ?>">Создать</a></div>