<?php $this->pageTitle = Yii::t('pages', 'Управление структурой'); ?>
<?php $this->bodyBlockTitle = Yii::t('pages', 'Структура сайта'); ?>
<?php 
$this->breadcrumbs=array(
	$this->module->getName(),
);
?>

<?php
$ajaxWndFunc = '';
        if(Yii::app()->controller->module->getAjaxWnd()){ //Если в настройках включено использование ajax окон
        	$ajaxWndFunc = '            
                function enableWysiwyg(){
                    var opts = {
                        doctype  : "<!DOCTYPE html>",
                        //   absoluteURLs    : true, //приводить адреса ссылок и изображений к абсолютным
                        //   allowSource : true, //разрешает редактирование HTML
                        //    fmAllow : true, //разрешить использование файлового менеджера
                        //    fmOpen  : Function(callback), // функция, которая будет вызвана, чтобы открыть файловый менеджер. Аргумент callback - функция которую редактор передает файловому менеджеру при открытии. Файловый менеджер должен вызывать эту функцию, передав ей url выбранного файла
                        //    toolbar : string, //имя тулбара : tiny, compact, normal, complete, maxi
                        /**     
                            tiny: только кнопки изменения стиля текста (жирный, наклонный, подчеркнутый, перечеркнутый, subscript, superscript)
                            compact: тоже, что и tiny + сохранить, отмена/повтор, выравнивание, списки, ссылки, полноэкранный режим
                            normal: compact + копировать/вставить, цвета, отступы, элементы, изображения
                            complete: normal + форматирование, размер и стиль шрифта
                            maxi: complete + таблицы
                        **/
                		cssClass : "el-rte inlineBlock",
                		styleWithCSS: "false",
                		height: 280,
                		lang     : "ru",
                		toolbar  : "complete",
                		cssfiles : ['.Yii::app()->baseUrl.'"/js/elrte/css/elrte-inner.css"]
              		}
                	$("#Pages_body").elrte(opts);
                }
                
                function onActivate(){
                    enableWysiwyg();
                    $("#window").data("kendoWindow").title($("#partialTitle").text()).center();
                    $("#partialTitle").remove();
                    $("select").kendoDropDownList();
                }
                
                $(document).ready(function() {
            		var window = $("#window");
                    
                    $("a[rel=\'createPages\']").bind("click", function(){
                        window.data("kendoWindow").refresh().open().center();
                        return false;
                    });
                    
                    if(!window.data("kendoWindow")){
                        window.kendoWindow({
                            width: "800px",
                            title: "Kendo Window",
                            content: {
                                url: "/backend/pages/ajax",
                                data: {
                                    eventType: "createItem",
                                    YII_CSRF_TOKEN: "'.Yii::app()->request->csrfToken.'",
                                },
                                dataType: "html",
                                type: "POST",
                                
                            },
                           // close: onClose,
                           // refresh: onRefresh,
                            activate: onActivate,
                            actions: ["Maximize", "Close"],
                            modal: true,
                            visible: false
                        });
                    }
                });
        	';
        }
        
        $scriptText = $ajaxWndFunc;
        
        $cs=Yii::app()->getClientScript();
        $cs->registerScript('Structure', $scriptText,CClientScript::POS_END);
?>

<div class="button-type2"><a rel="createPages" href="<?php echo Yii::app()->createUrl('/pages/admin/create/'); ?>">Создать раздел / страницу</a></div>
<div id="structure-tree" class="treeWidget">
<?php 
$this->widget('StructureTree', array(
	'data' => Pages::model()->findByPk(1)->getTreeViewData(true), // Корневой элемент из БД. true - вывод корневого узла дерева
	'animated' => 'fast', //скорость анимаци свертывания/развертывания
	'collapsed' => false, //по умолчанию все узлы свернуты
	'persist' => 'location', //метод запоминания открытого узла
	'unique' => false, //при открытии одного узла, будут закрываться остальные
	'htmlOptions' => array(
		'class' => 'siteTree',
	),
));
?>
</div>
<div id="ajaxRes"></div>