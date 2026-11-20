<?php $this->pageTitle = Yii::t('pages', 'Редактирование структуры'); ?>
<?php $this->bodyBlockTitle = Yii::t('pages', 'Редактирование элемента структуры'); ?>

<h1 id="partialTitle" style="display: none;"><?php echo $this->bodyBlockTitle; ?></h1>
<?php 
$this->breadcrumbs=array(
	$this->module->getName()=>array('list'),
	$model->name=>array('view','id'=>$model->id),
	'Редактирование',
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

<?php
/**
//Модификация дерева
//перемещение узлов
$x100 = Pages::model()->findByPk(10);
$c200 = Pages::model()->findByPk(9);
$samsung = Pages::model()->findByPk(7);
$x100->moveAsFirst($samsung);
$c200->moveBefore($x100);
$mobile_phones = Pages::model()->findByPk(1);
$samsung->moveAsFirst($mobile_phones);
$iphone = Pages::model()->findByPk(6);
$iphone->moveAsFirst($mobile_phones);

$cars = Pages::model()->findByPk(2);
$audi = Pages::model()->findByPk(3);
$ford = Pages::model()->findByPk(4);
$mercedes = Pages::model()->findByPk(5);
foreach(array($audi, $ford, $mercedes) as $category) $category->moveAsLast($cars);

//Перемещение узла в качестве нового корня
$node = Pages::model()->findByPk(10);
$node->moveAsRoot();

//Идентификация узлов дерева
$root = Pages::model()->findByPk(1);
CVarDumper::dump($root->isRoot()); //true
CVarDumper::dump($root->isLeaf()); //false
$node = Pages::model()->findByPk(9);
CVarDumper::dump($node->isDescendantOf($root)); //true
CVarDumper::dump($node->isRoot()); //false
CVarDumper::dump($root->isLeaf()); //true
$samsung = Pages::model()->findByPk(7);
CVarDumper::dump($node->isDescendantOf($samsung)); //true
**/