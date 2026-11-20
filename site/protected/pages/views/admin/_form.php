<?php
$cs = Yii::app()->clientScript;

$cs->registerScriptFile(Yii::app()->theme->baseUrl."/js/translateFn.js",CClientScript::POS_END);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'pages-form',
	//'action'=>'/backend/pages/create',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Поля, отмеченные звездочкой (<span class="required">*</span>) обязательны для заполнения.</p>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="tabStrip" id="createPageTabs">
		<ul>
			<li class="k-state-active">Основные параметры</li>
			<li>Дополнительные параметры</li>
		</ul>
		<div>
			<table class="form createPage" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model, 'parent_id'); ?></td>
				<td><?php echo $form->dropDownList($model, 'parent_id', CHtml::listData(Pages::model()->findAll(array('condition' =>'id > 0 and module_id < 1','order' => 'lft')), 'id', 'nameExt')); ?></td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'module_id'); ?></td>
				<td>
					<?php //echo $form->textField($model,'module_id'); ?>
      		  		<?php echo $form->dropDownList($model, 'module_id', array('Раздел', 'Страница')); ?>
					<?php echo $form->error($model,'module_id'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'name'); ?></td>
				<td>
					<?php echo $form->textField($model,'name',array('maxlength'=>255, 'class'=>'fieldGreyText', 'onblur'=>'if(this.value == \'\') translateInInput( $(\'#Pages_title\'), this); else translateInInput( this, this);')); ?>
					<?php echo $form->error($model,'name'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'title'); ?></td>
				<td>
					<?php echo $form->textField($model,'title',array('maxlength'=>255, 'onkeyup'=>'translateInInput(this, $(\'#Pages_name\'));')); ?>
					<?php echo $form->error($model,'title'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'anons'); ?></td>
				<td>
					<?php echo $form->textArea($model,'anons'); ?>
					<?php echo $form->error($model,'anons'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'body'); ?></td>
				<td class="formNoRightPadding">
					<?php echo $form->textArea($model,'body'); ?>
					<?php echo $form->error($model,'body'); ?>
				</td>
			</tr>
			</table>
		</div>
		<div>
			<table class="form createPage" cellpadding="0" cellspacing="0">
				<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'template_id'); ?></td>
				<td>
					<?php echo $form->dropDownList($model, 'template_id', array('Шаблон по умолчанию')); ?>
					<?php echo $form->error($model,'template_id'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'create_time'); ?></td>
				<td>
					<?php echo $form->textField($model,'create_time', array('class' => 'datePicker')); ?>
					<?php echo $form->error($model,'create_time'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'menu_enable'); ?></td>
				<td>				
					<?php echo $form->checkBox($model,'menu_enable'); ?>
					<?php echo $form->error($model,'menu_enable'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'menu_order'); ?></td>
				<td>
					<?php echo $form->textField($model,'menu_order'); ?>
					<?php echo $form->error($model,'menu_order'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'map_enable'); ?></td>
				<td>				
					<?php echo $form->checkBox($model,'map_enable'); ?>
					<?php echo $form->error($model,'map_enable'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'map_order'); ?></td>
				<td>				
					<?php echo $form->textField($model,'map_order'); ?>
					<?php echo $form->error($model,'map_order'); ?>
				</td>
			</tr>
			<tr valign="top">
				<td class="formLabel"><?php echo $form->labelEx($model,'redirect'); ?></td>
				<td>				
					<?php echo $form->textField($model,'redirect',array('maxlength'=>255)); ?>
					<?php echo $form->error($model,'redirect'); ?>
				</td>
			</tr>
			</table>
		</div>
	</div>
	<div class="buttonsDiv">
				<?php
				if(Yii::app()->controller->module->getAjaxWnd() && Yii::app()->request->isAjaxRequest){
					
					echo '<div class="button-type2"><a href="#" id="formSubmitButton">'.($model->isNewRecord ? Yii::t('artcms', 'Создать') : Yii::t('artcms', 'Сохранить')).'</a></div>
        		<div class="button-type3"><a href="#" id="formResetButton">'.Yii::t('artcms', 'Отменить').'</a></div>';
					/**
					echo CHtml::ajaxSubmitButton(($model->isNewRecord ? 'Создать' : 'Сохранить'), '/backend/pages/ajax', 
						array(
							'type' => 'POST',
							'data' => array(				
								'formFields' => 'js:jQuery(this).parents("form").serializeArray()',			
								'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken,
								'eventType' => 'saveItem',
							),
							'update' => '#ajaxRes',
						),
						array(
							// Меняем тип элемента на submit, чтобы у пользователей с отключенным JS все было хорошо
							'type' => 'submit'
						)
					);**/
				}else{
					echo '<div class="button-type2">'.CHtml::submitButton($model->isNewRecord ? Yii::t('artcms', 'Создать') : Yii::t('artcms', 'Сохранить')).'</div><div class="button-type3">'.CHtml::link(Yii::t('artcms', 'Отменить'), '/backend/pages').'</div>';
				}
				  ?>
     </div>
    <script>
    	$(document).ready(function(){
    		$('#createPageTabs').kendoTabStrip({
    			animation: {
    				open: {
    					effects: "fadeIn"
    				}
    			}
    		});
    		
			$('.datePicker').kendoDatePicker({
				format: "dd.MM.yyyy",
				value: new Date(<?php echo date("Y, m-1, d"); ?>)
			});
			<?php if(Yii::app()->request->isAjaxRequest): ?>
			$('#pages-form').yiiactiveform(
				{
					'attributes':
						[{
							'id':'Pages_name',
							'inputID':'Pages_name',
							'errorID':'Pages_name_em_',
							'model':'Pages',
							'name':'Pages[name]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_title',
							'inputID':'Pages_title',
							'errorID':'Pages_title_em_',
							'model':'Pages',
							'name':'Pages[title]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_anons',
							'inputID':'Pages_anons',
							'errorID':'Pages_anons_em_',
							'model':'Pages',
							'name':'Pages[anons]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_body',
							'inputID':'Pages_body',
							'errorID':'Pages_body_em_',
							'model':'Pages',
							'name':'Pages[body]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_create_time',
							'inputID':'Pages_create_time',
							'errorID':'Pages_create_time_em_',
							'model':'Pages',
							'name':'Pages[create_time]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_template_id',
							'inputID':'Pages_template_id',
							'errorID':'Pages_template_id_em_',
							'model':'Pages',
							'name':'Pages[template_id]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_module_id',
							'inputID':'Pages_module_id',
							'errorID':'Pages_module_id_em_',
							'model':'Pages',
							'name':'Pages[module_id]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_menu_order',
							'inputID':'Pages_menu_order',
							'errorID':'Pages_menu_order_em_',
							'model':'Pages',
							'name':'Pages[menu_order]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_menu_enable',
							'inputID':'Pages_menu_enable',
							'errorID':'Pages_menu_enable_em_',
							'model':'Pages',
							'name':'Pages[menu_enable]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_map_order',
							'inputID':'Pages_map_order',
							'errorID':'Pages_map_order_em_',
							'model':'Pages',
							'name':'Pages[map_order]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_map_enable',
							'inputID':'Pages_map_enable',
							'errorID':'Pages_map_enable_em_',
							'model':'Pages',
							'name':'Pages[map_enable]',
							'enableAjaxValidation':true
						},{
							'id':'Pages_redirect',
							'inputID':'Pages_redirect',
							'errorID':'Pages_redirect_em_',
							'model':'Pages',
							'name':'Pages[redirect]',
							'enableAjaxValidation':true
						}],
						'summaryID':'pages-form_es_'
				});

			
			$('#formSubmitButton').click(function(){
				$("#Pages_body").elrte('updateSource');
																
				var formFields = $(this).parents("form").serializeArray();
								
				$.ajax({
					url: "/backend/pages/ajax",
                    type: "POST",
                    data:{
                        eventType: "saveItem",
                        formFields: $.toJSON(formFields),                        
                        YII_CSRF_TOKEN: "<?php echo Yii::app()->request->csrfToken; ?>",
                    },
                    dataType: "text",
        			success: function(data){
        				$('#ajaxRes').html(data);
        				//console.log(data);			
        			},
        			error: function(xhr, textStatus, errorThrown){
        				console.log(textStatus);
        			}
				});
				return false;
			});
			$('#formResetButton').click(function(){
				$('#window').data("kendoWindow").close();
				return false;
			});
			
			<?php endif; ?>
		});
	</script>
<?php /** ?>	
	<table class="form createPage" cellpadding="0" cellspacing="0">
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model, 'parent_id'); ?></td>
			<td><?php echo $form->dropDownList($model, 'parent_id', CHtml::listData(Pages::model()->findAll(array('condition' =>'id > 0 and module_id < 1','order' => 'lft')), 'id', 'nameExt')); ?></td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'name'); ?></td>
			<td>
				<?php echo $form->textField($model,'name',array('maxlength'=>255)); ?>
				<?php echo $form->error($model,'name'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'title'); ?></td>
			<td>
				<?php echo $form->textField($model,'title',array('maxlength'=>255)); ?>
				<?php echo $form->error($model,'title'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'anons'); ?></td>
			<td>
				<?php echo $form->textArea($model,'anons'); ?>
				<?php echo $form->error($model,'anons'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'body'); ?></td>
			<td class="formNoRightPadding">
				<?php echo $form->textArea($model,'body'); ?>
				<?php echo $form->error($model,'body'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'create_time'); ?></td>
			<td>
				<?php //echo $form->textField($model,'create_time'); ?>
        		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            		'name' => 'Pages[create_time]',
            		//additional javascript options for the date picker plugin
            		'options' => array(
                		'showAnim' => 'fold',
            		),
        		)); ?>
				<?php echo $form->error($model,'create_time'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'template_id'); ?></td>
			<td>
				<?php echo $form->dropDownList($model, 'template_id', array('Шаблон по умолчанию')); ?>
				<?php echo $form->error($model,'template_id'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'module_id'); ?></td>
			<td>
				<?php //echo $form->textField($model,'module_id'); ?>
        		<?php echo $form->dropDownList($model, 'module_id', array('Раздел', 'Страница')); ?>
				<?php echo $form->error($model,'module_id'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'menu_order'); ?></td>
			<td>
				<?php echo $form->textField($model,'menu_order'); ?>
				<?php echo $form->error($model,'menu_order'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'menu_enable'); ?></td>
			<td>				
				<?php echo $form->checkBox($model,'menu_enable'); ?>
				<?php echo $form->error($model,'menu_enable'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'map_order'); ?></td>
			<td>				
				<?php echo $form->textField($model,'map_order'); ?>
				<?php echo $form->error($model,'map_order'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'map_enable'); ?></td>
			<td>				
				<?php echo $form->checkBox($model,'map_enable'); ?>
				<?php echo $form->error($model,'map_enable'); ?>
			</td>
		</tr>
		<tr valign="top">
			<td class="formLabel"><?php echo $form->labelEx($model,'redirect'); ?></td>
			<td>				
				<?php echo $form->textField($model,'redirect',array('maxlength'=>255)); ?>
				<?php echo $form->error($model,'redirect'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="formButtons">
				<div class="button-type2"><?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?></div>
        		<div class="button-type3"><?php echo CHtml::resetButton('Отменить'); ?></div>
        	</td>
		</tr>
	</table> <?php **/ ?>
<?php /** ?>
    <div class="row">
        <?php echo $form->labelEx($model, 'parent_id'); ?>
        <?php echo $form->dropDownList($model, 'parent_id', CHtml::listData(Pages::model()->findAll(array('condition' =>'id > 0 and module_id < 1','order' => 'lft')), 'id', 'nameExt')); ?>
    </div>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'anons'); ?>
		<?php echo $form->textArea($model,'anons',array('rows'=>4, 'cols'=>50)); ?>
		<?php echo $form->error($model,'anons'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'body'); ?>
		<?php echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'body'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'create_time'); ?>
		<?php //echo $form->textField($model,'create_time'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'Pages[create_time]',
            //additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
            ),
        )); ?>
		<?php echo $form->error($model,'create_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_time'); ?>
		<?php //echo $form->textField($model,'update_time'); ?>
        <?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
            'name' => 'Pages[update_time]',
            //additional javascript options for the date picker plugin
            'options' => array(
                'showAnim' => 'fold',
            ),
        )); ?>
		<?php echo $form->error($model,'update_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'author_id'); ?>
		<?php echo $form->textField($model,'author_id'); ?>
		<?php echo $form->error($model,'author_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'template_id'); ?>
		<?php //echo $form->textField($model,'template_id'); ?>
        <?php echo $form->dropDownList($model, 'template_id', array('Шаблон по умолчанию')); ?>
		<?php echo $form->error($model,'template_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'module_id'); ?>
		<?php //echo $form->textField($model,'module_id'); ?>
        <?php echo $form->dropDownList($model, 'module_id', array('Раздел', 'Страница')); ?>
		<?php echo $form->error($model,'module_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'menu_order'); ?>
		<?php echo $form->textField($model,'menu_order'); ?>
		<?php echo $form->error($model,'menu_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'menu_enable'); ?>
		<?php echo $form->checkBox($model,'menu_enable'); ?>
		<?php echo $form->error($model,'menu_enable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'map_order'); ?>
		<?php echo $form->textField($model,'map_order'); ?>
		<?php echo $form->error($model,'map_order'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'map_enable'); ?>
		<?php echo $form->checkBox($model,'map_enable'); ?>
		<?php echo $form->error($model,'map_enable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'redirect'); ?>
		<?php echo $form->textField($model,'redirect',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'redirect'); ?>
	</div>

	<div class="row buttons">
		<div class="button-type2"><?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?></div>
        <div class="button-type3"><?php echo CHtml::resetButton('Отменить'); ?></div>
	</div>
	
<?php **/ ?>

<?php $this->endWidget(); ?>
