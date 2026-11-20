<div class="form">
<script>
function deleteMenuItem(targetId){
	if(!confirm("Вы уверены, что хотите удалить данный элемент?")) return false;
	
	$('#'+targetId).remove();
	
	return false;
}
function showMenuItemsTree(e){
	if($('#addItemsTree').is(':hidden')){
		$('#addItemsTree').fadeIn('fast');
		
		var clickX = e.clientX, 
			clickY = e.clientY;
			
		$('#addItemsTree').offset({top: clickY+20, left: clickX});
	}
	
	
	return false;
}

$(document).ready(function(){
	$("#addItemsTree li a").click(function(){
		
		if($(this).parents('li').is('.k-item')){
			var itemId = $(this).parents('li.k-item').attr('id').replace("node_", '');
			//console.log(itemId);
			if($('#menuItem_'+itemId).is('li')){
				$('#menuItem_'+itemId).addClass('selected');
				
				var errorTimer = setTimeout(function(){
					$('#menuItem_'+itemId).removeClass('selected');
				}, 2000);
				return false;
			}
			$('#menuItems ul').append('<li id="menuItem_'+itemId+'"><input type="hidden" value="'+itemId+'" name="menuItems[]"><div class="menuItemName fleft"><span class="itemIcon link"></span><a class="disableLink" href="#">'+$(this).text()+'</a></div><div class="menuItemAction fright"><div class="treeActionButton delete"><a href="#" class="deleteMenuItem" onclick="deleteMenuItem(\'menuItem_'+itemId+'\'); return false;"></a></div></div><div class="clear"></div></li>');
			//console.log('click on '+itemId);
		}
		
		return false;
	});
	
	$(".disableLink").click(function(){
		
	
		return false;
	});
	
});
</script>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'menu-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные звездочкой (<span class="required">*</span>) обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model, 'menuItems'); ?>
		<div class="button-type5"><a href="#" onclick="showMenuItemsTree(event); return false;">Добавить</a></div>
		
		<div id="menuItems">
			<?php
			$this->widget('MenuWidget', array(
                            'encodeLabel' => false,
                            'menuName' => $model->name,
                            'showSubMenu' => false,
                            'itemsLink' => false,
                            'extLabel' => '<input type="hidden" name="menuItems[]" value="{id}" /><div class="menuItemName fleft"><input type="text" class="itemSort" /><span class="itemIcon link"></span>{menu}</div><div class="menuItemAction fright"><div class="treeActionButton delete"><a href="#" onclick="deleteMenuItem(\'menuItem_{id}\'); return false;" class="deleteMenuItem"></a></div></div><div class="clear"></div>'
			));
			
			?>
		</div>
		
		<div id="addItemsTree" style="display: none;">
			<a href="#" id="addItemsTreeClose"></a>
			<?php 
			$this->widget('pagesTreeWidget', array(
				'data' => Pages::model()->findByPk(1)->getTreeViewData(true), // Корневой элемент из БД. true - вывод корневого узла дерева	'
				'htmlOptions' => array(
					'class' => 'pagesTreeWidget kendoTree',
				),
			));
			?>
			<script>
			$(document).ready(function(){
				$('#addItemsTreeClose').click(function(){
					$(this).parent('#addItemsTree').fadeOut("fast");
					return false;
				});

			});
			</script>
		</div>
	</div>
	
	


	<div class="row">
		<?php echo $form->labelEx($model,'manual'); ?>
        <?php echo $form->textArea($model,'manual',array('cols'=>47, 'rows'=>5)); ?>
		<?php echo $form->error($model,'manual'); ?>
	</div>

	<div class="row buttons">
		<div class="button-type2 noMargin"><?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?></div>
        <div class="button-type3 noMargin"><?php echo CHtml::link(Yii::t('artcms', 'Отменить'), '/backend/menu'); ?></div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->