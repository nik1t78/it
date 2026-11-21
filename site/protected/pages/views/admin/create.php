<?php $this->pageTitle = Yii::t('pages', 'Управление структурой'); ?>
<?php $this->bodyBlockTitle = Yii::t('pages', 'Создание элемента структуры'); ?>

<h1 id="partialTitle" style="display: none;"><?php echo $this->bodyBlockTitle; ?></h1>
<?php 
$this->breadcrumbs=array(
	$this->module->getName()=>array('list'),
	'Создание элемента',
);

		if(!Yii::app()->request->isAjaxRequest){ //Если запрос не асинхронный, подключаем js скрипт, который выводит wysisyg-редактор	
            echo '<script> $(document).ready(function(){
                    //$("select").kendoDropDownList();
                    
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
                		lang     : "ru",
                		height   : 300,
                		toolbar  : "maxi",
                		styleWithCSS: "false",
                		cssfiles : ['.Yii::app()->baseUrl.'"/js/elrte/css/elrte-inner.css"]
              		}
                	$("#Pages_body").elrte(opts);
                 });</script>';
        }
?>
<?php $this->widget('AFlashMessages'); ?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>

<div id="ajaxRes"></div>
